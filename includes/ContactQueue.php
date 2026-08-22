<?php
declare(strict_types=1);

final class ContactQueue
{
    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /** @param array<string, mixed> $message */
    public function enqueue(array $message, string $requestId): bool
    {
        if (!is_dir($this->directory) && !mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            return false;
        }
        $payload = json_encode(['request_id' => $requestId, 'message' => $message], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $temporary = tempnam($this->directory, 'contact-');
        if ($temporary === false) {
            return false;
        }
        if (file_put_contents($temporary, $payload, LOCK_EX) === false) {
            @unlink($temporary);
            return false;
        }
        return rename($temporary, $this->directory . DIRECTORY_SEPARATOR . $requestId . '.json');
    }
}
