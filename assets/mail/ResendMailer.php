<?php

declare(strict_types=1);

final class DeliveryResult
{
    public bool $successful;
    public int $status;
    public ?string $providerId;
    public string $error;

    public function __construct(bool $successful, int $status, ?string $providerId, string $error)
    {
        $this->successful = $successful;
        $this->status = $status;
        $this->providerId = $providerId;
        $this->error = $error;
    }
}

final class ResendMailer
{
    private const ENDPOINT = 'https://api.resend.com/emails';
    private const MAX_ATTEMPTS = 3;

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /** @param array<string, mixed> $message */
    public function send(array $message, string $idempotencyKey): DeliveryResult
    {
        $payload = json_encode($message, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $lastStatus = 0;
        $lastError = 'Unknown delivery error';

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            $startedAt = microtime(true);
            $handle = curl_init(self::ENDPOINT);
            curl_setopt_array($handle, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 3,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Type: application/json',
                    'Idempotency-Key: ' . $idempotencyKey,
                    'User-Agent: omar-pembe-portfolio/1.0',
                ],
            ]);

            $response = curl_exec($handle);
            $curlError = curl_error($handle);
            $lastStatus = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
            curl_close($handle);

            $decoded = is_string($response) ? json_decode($response, true) : null;
            $providerId = is_array($decoded) && isset($decoded['id']) ? (string) $decoded['id'] : null;
            $lastError = $curlError !== '' ? $curlError : $this->providerError($decoded);
            $this->logAttempt($idempotencyKey, $attempt, $lastStatus, $providerId, $lastError, $startedAt);

            if ($lastStatus >= 200 && $lastStatus < 300 && $providerId !== null) {
                return new DeliveryResult(true, $lastStatus, $providerId, '');
            }

            $transient = $response === false || $lastStatus === 429 || $lastStatus >= 500;
            if (!$transient || $attempt === self::MAX_ATTEMPTS) {
                break;
            }
            usleep(250000 * (2 ** ($attempt - 1)));
        }

        return new DeliveryResult(false, $lastStatus, null, $lastError);
    }

    /** @param mixed $decoded */
    private function providerError(mixed $decoded): string
    {
        if (!is_array($decoded)) {
            return 'Invalid provider response';
        }
        return isset($decoded['message']) ? (string) $decoded['message'] : 'Provider rejected delivery';
    }

    private function logAttempt(string $requestId, int $attempt, int $status, ?string $providerId, string $error, float $startedAt): void
    {
        error_log((string) json_encode([
            'event' => 'contact.delivery',
            'request_id' => $requestId,
            'attempt' => $attempt,
            'http_status' => $status,
            'provider_id' => $providerId,
            'error' => $error === '' ? null : $error,
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ], JSON_UNESCAPED_SLASHES));
    }
}
