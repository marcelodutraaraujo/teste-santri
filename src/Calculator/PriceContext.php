<?php
declare(strict_types=1);

namespace App\Calculator;

class PriceContext
{
    public function __construct(
        public float $basePrice,
        public int $quantity,
        public string $customerType,
        public float $weight,
        public string $state,
    ) {}

    public function cacheKey(): string
    {
        return md5(json_encode([
            $this->basePrice,
            $this->quantity,
            $this->customerType,
            $this->weight,
            $this->state
        ]));
    }
}
