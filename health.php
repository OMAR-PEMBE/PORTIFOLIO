<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$defaultData = __DIR__ . DIRECTORY_SEPARATOR . 'data';
$defaultUploads = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'projects';
$checks = [
    'data_storage' => environment('APP_DATA_DIR', $defaultData) ?? $defaultData,
    'upload_storage' => environment('UPLOAD_STORAGE_DIR', $defaultUploads) ?? $defaultUploads,
];
$healthy = true;
foreach ($checks as $name => $directory) {
    $checks[$name] = is_dir($directory) && is_writable($directory);
    $healthy = $healthy && $checks[$name];
}

http_response_code($healthy ? 200 : 503);
echo json_encode(['status' => $healthy ? 'ok' : 'unhealthy', 'checks' => $checks], JSON_UNESCAPED_SLASHES);
