<?php
declare(strict_types=1);

namespace Tests\Mocks;

use App\Cache\CacheInterface;

class FakeCache implements CacheInterface
{
    private array $data = [];

    public function get(string $key): ?float
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, float $value): void
    {
        $this->data[$key] = $value;
    }
}
