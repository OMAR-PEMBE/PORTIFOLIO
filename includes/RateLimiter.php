<?php
declare(strict_types=1);

final class RateLimiter
{
    private string $directory;
    private ?string $redisDsn;

    public function __construct(string $directory, ?string $redisDsn = null)
    {
        $this->directory = $directory;
        $this->redisDsn = $redisDsn;
    }

    public function consume(string $clientAddress, int $maximum, int $windowSeconds): int
    {
        if ($this->redisDsn !== null && class_exists('Redis')) {
            return $this->consumeRedis($clientAddress, $maximum, $windowSeconds);
        }
        return $this->consumeFile($clientAddress, $maximum, $windowSeconds);
    }

    private function consumeRedis(string $clientAddress, int $maximum, int $windowSeconds): int
    {
        $parts = parse_url($this->redisDsn);
        if (!is_array($parts) || !isset($parts['host'])) {
            return -1;
        }
        try {
            $redis = new Redis();
            $redis->connect($parts['host'], (int) ($parts['port'] ?? 6379), 1.0);
            $key = 'portfolio:contact:' . hash('sha256', $clientAddress);
            $count = $redis->incr($key);
            if ($count === 1) {
                $redis->expire($key, $windowSeconds);
            }
            return $count <= $maximum ? 1 : 0;
        } catch (Throwable $exception) {
            error_log(json_encode(['event' => 'contact.redis_rate_limit_error', 'error' => $exception->getMessage()]));
            return -1;
        }
    }

    private function consumeFile(string $clientAddress, int $maximum, int $windowSeconds): int
    {
        $file = $this->directory . DIRECTORY_SEPARATOR . 'portfolio-contact-' . hash('sha256', $clientAddress) . '.json';
        $handle = @fopen($file, 'c+');
        if ($handle === false || !flock($handle, LOCK_EX)) {
            if (is_resource($handle)) {
                fclose($handle);
            }
            return -1;
        }

        $contents = stream_get_contents($handle);
        $timestamps = is_string($contents) && $contents !== '' ? json_decode($contents, true) : [];
        $timestamps = is_array($timestamps) ? $timestamps : [];
        $cutoff = time() - $windowSeconds;
        $timestamps = array_values(array_filter($timestamps, static fn ($value): bool => is_int($value) && $value >= $cutoff));
        $allowed = count($timestamps) < $maximum;
        if ($allowed) {
            $timestamps[] = time();
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, (string) json_encode($timestamps));
            fflush($handle);
        }
        flock($handle, LOCK_UN);
        fclose($handle);
        return $allowed ? 1 : 0;
    }
}
