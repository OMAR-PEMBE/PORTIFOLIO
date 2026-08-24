<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

function siteContentJsonPath(): string
{
    $default = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
    $directory = environment('APP_DATA_DIR', $default) ?? $default;
    if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create application data storage.');
    }
    return rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . 'site-content.json';
}

function siteContent(): array
{
    static $content;
    if ($content !== null) {
        return $content;
    }
    $jsonPath = siteContentJsonPath();
    if (is_file($jsonPath)) {
        $decoded = json_decode((string) file_get_contents($jsonPath), true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Site content JSON is invalid.');
        }
        return $content = $decoded;
    }
    $defaults = require dirname(__DIR__) . '/data/site-content.php';
    return $content = is_array($defaults) ? $defaults : [];
}

function saveSiteContent(array $content): void
{
    $path = siteContentJsonPath();
    $temporary = tempnam(dirname($path), 'site-content-');
    if ($temporary === false || file_put_contents($temporary, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . PHP_EOL, LOCK_EX) === false || !rename($temporary, $path)) {
        if ($temporary !== false) {
            @unlink($temporary);
        }
        throw new RuntimeException('Unable to save site content.');
    }
}
