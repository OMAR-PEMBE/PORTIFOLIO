<?php
declare(strict_types=1);

function siteContent(): array
{
    static $content;
    if ($content !== null) {
        return $content;
    }
    $jsonPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'site-content.json';
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
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'site-content.json';
    $temporary = tempnam(dirname($path), 'site-content-');
    if ($temporary === false || file_put_contents($temporary, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . PHP_EOL, LOCK_EX) === false || !rename($temporary, $path)) {
        if ($temporary !== false) {
            @unlink($temporary);
        }
        throw new RuntimeException('Unable to save site content.');
    }
}
