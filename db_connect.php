<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli(
    requiredEnvironment('DB_HOST'),
    requiredEnvironment('DB_USER'),
    environment('DB_PASSWORD', '') ?? '',
    requiredEnvironment('DB_NAME')
);
$conn->set_charset('utf8mb4');
