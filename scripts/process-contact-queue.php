<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../assets/mail/ResendMailer.php';

$directory = requiredEnvironment('CONTACT_QUEUE_DIR');
$apiKey = requiredEnvironment('RESEND_API_KEY');
$files = glob($directory . DIRECTORY_SEPARATOR . '*.json') ?: [];

foreach ($files as $file) {
    $payload = json_decode((string) file_get_contents($file), true);
    if (!is_array($payload) || !isset($payload['request_id'], $payload['message']) || !is_array($payload['message'])) {
        rename($file, $file . '.failed');
        continue;
    }
    $result = (new ResendMailer($apiKey))->send($payload['message'], (string) $payload['request_id']);
    if ($result->successful) {
        unlink($file);
    }
}