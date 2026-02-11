<?php
declare(strict_types=1);

namespace App\Cache;

class FileCache implements CacheInterface
{
    private string $path;

    public function __construct(string $path = '/tmp/php-cache/')
    {
        $this->path = rtrim($path, '/') . '/';
    }

    public function get(string $key): ?float
    {
        $file = $this->path . md5($key);
        return file_exists($file) ? (float) file_get_contents($file) : null;
    }

    public function set(string $key, float $value): void
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
        file_put_contents($this->path . md5($key), (string)$value);
    }
}
