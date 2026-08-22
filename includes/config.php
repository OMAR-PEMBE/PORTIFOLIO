<?php
declare(strict_types=1);

$environmentFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
if (is_readable($environmentFile)) {
    foreach (file($environmentFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || strpos($line, '=') === false) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if ($name !== '' && getenv($name) === false) {
            putenv($name . '=' . $value);
        }
    }
}

function environment(string $name, ?string $default = null): ?string
{
    $value = getenv($name);
    if ($value === false || trim($value) === '') {
        return $default;
    }
    return trim($value);
}

function requiredEnvironment(string $name): string
{
    $value = environment($name);
    if ($value === null) {
        throw new RuntimeException("Missing required environment variable: {$name}");
    }
    return $value;
}
