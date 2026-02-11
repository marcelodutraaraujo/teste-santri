<?php
declare(strict_types=1);

namespace App\Cache;

interface CacheInterface
{
    public function get(string $key): ?float;

    public function set(string $key, float $value): void;
}
