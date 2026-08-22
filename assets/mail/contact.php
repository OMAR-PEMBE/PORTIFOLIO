<?php

declare(strict_types=1);

require_once __DIR__ . '/ResendMailer.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/RateLimiter.php';
require_once __DIR__ . '/../../includes/ContactQueue.php';

function respond(string $message, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: text/html; charset=utf-8');
    echo $message;
    exit;
}

function configuredEmail(string $name): ?string
{
    $value = getenv($name);
    if ($value === false || trim($value) === '') {
        return null;
    }
    $email = trim($value);
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? $email : null;
}

function withinLength(string $value, int $maximum): bool
{
    return mb_strlen($value, 'UTF-8') <= $maximum;
}

function consumeRateLimit(string $clientAddress, int $maximum = 5, int $windowSeconds = 900): int
{
    $redisDsn = environment('REDIS_DSN');
    return (new RateLimiter(sys_get_temp_dir(), $redisDsn))->consume($clientAddress, $maximum, $windowSeconds);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond('', 405);
}

$requestId = bin2hex(random_bytes(16));
header('X-Request-ID: ' . $requestId);

// Silently accept honeypot submissions so automated senders receive no useful signal.
if (trim((string) filter_input(INPUT_POST, 'website')) !== '') {
    error_log((string) json_encode(['event' => 'contact.spam_rejected', 'request_id' => $requestId]));
    respond('<div class="alert alert-success">Thank you. Your message has been submitted.</div>');
}

$rateLimit = consumeRateLimit((string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
if ($rateLimit === 0) {
    header('Retry-After: 900');
    error_log((string) json_encode(['event' => 'contact.rate_limited', 'request_id' => $requestId]));
    respond('<div class="alert alert-error">Too many messages were submitted. Please try again later.</div>', 429);
}
if ($rateLimit < 0) {
    error_log((string) json_encode(['event' => 'contact.rate_limit_error', 'request_id' => $requestId]));
    respond('<div class="alert alert-error">The contact form is temporarily unavailable.</div>', 503);
}

$address = configuredEmail('CONTACT_RECIPIENT');
$fromAddress = configuredEmail('CONTACT_FROM');
$apiKey = trim((string) (getenv('RESEND_API_KEY') ?: ''));
if ($address === null || $fromAddress === null || $apiKey === '') {
    error_log((string) json_encode(['event' => 'contact.configuration_error', 'request_id' => $requestId]));
    respond('<div class="alert alert-error">The contact form is temporarily unavailable. Please use the WhatsApp link instead.</div>', 503);
}

$name = trim((string) filter_input(INPUT_POST, 'name'));
$email = trim((string) filter_input(INPUT_POST, 'email'));
$phone = trim((string) filter_input(INPUT_POST, 'phone'));
$comments = trim((string) filter_input(INPUT_POST, 'comments'));

if ($name === '' || !withinLength($name, 100)) {
    respond('<div class="alert alert-error">Enter a valid name.</div>', 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !withinLength($email, 254)) {
    respond('<div class="alert alert-error">Enter a valid email address.</div>', 422);
}
if ($phone === '' || !withinLength($phone, 40)) {
    respond('<div class="alert alert-error">Enter a valid phone number.</div>', 422);
}
if ($comments === '' || !withinLength($comments, 5000)) {
    respond('<div class="alert alert-error">Enter a message of no more than 5,000 characters.</div>', 422);
}

$message = [
    'from' => 'Portfolio Website <' . $fromAddress . '>',
    'to' => [$address],
    'reply_to' => $email,
    'subject' => 'Portfolio contact from ' . $name,
    'text' => "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\n{$comments}",
];

$queueDirectory = environment('CONTACT_QUEUE_DIR');
if ($queueDirectory !== null) {
    if (!(new ContactQueue($queueDirectory))->enqueue($message, $requestId)) {
        error_log((string) json_encode(['event' => 'contact.queue_error', 'request_id' => $requestId]));
        respond('<div class="alert alert-error">Unable to submit your message right now. Please try again later.</div>', 503);
    }
    respond('<div class="alert alert-success"><h3>Email Sent Successfully.</h3><p>Thank you. Your message has been submitted.</p></div>');
}

$delivery = (new ResendMailer($apiKey))->send($message, $requestId);
if (!$delivery->successful) {
    respond('<div class="alert alert-error">Unable to send your message right now. Please try again later.</div>', 502);
}

$safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
respond(
    "<div class='alert alert-success'>"
    . '<h3>Email Sent Successfully.</h3>'
    . "<p>Thank you <strong>{$safeName}</strong>, your message has been submitted.</p>"
    . '</div>'
);
