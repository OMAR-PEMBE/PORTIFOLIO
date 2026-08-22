<?php
declare(strict_types=1);

final class ProjectStore
{
    private string $jsonPath;
    private string $phpPath;

    public function __construct(string $rootDirectory)
    {
        $this->jsonPath = $rootDirectory . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'projects.json';
        $this->phpPath = $rootDirectory . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'projects.php';
    }

    /** @return array<string, array<string, mixed>> */
    public function records(): array
    {
        if (is_file($this->jsonPath)) {
            $decoded = json_decode((string) file_get_contents($this->jsonPath), true);
            if (!is_array($decoded)) {
                throw new RuntimeException('Project JSON is invalid.');
            }
            return $decoded;
        }

        $records = require $this->phpPath;
        if (!is_array($records)) {
            throw new RuntimeException('Project data is invalid.');
        }
        return $records;
    }

    /** @param array<string, array<string, mixed>> $records */
    public function save(array $records): void
    {
        $directory = dirname($this->jsonPath);
        $temporary = tempnam($directory, 'projects-');
        if ($temporary === false) {
            throw new RuntimeException('Unable to create project storage.');
        }
        $json = json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        if (file_put_contents($temporary, $json . PHP_EOL, LOCK_EX) === false || !rename($temporary, $this->jsonPath)) {
            @unlink($temporary);
            throw new RuntimeException('Unable to save project storage.');
        }
    }
}
