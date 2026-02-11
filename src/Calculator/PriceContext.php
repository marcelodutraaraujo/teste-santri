<?php
declare(strict_types=1);

namespace App\Calculator;

use App\Exception\InvalidWeightException;
use App\Exception\InvalidQuantityException;

class PriceContext
{
    public function __construct(
        public float $basePrice,
        public int $quantity,
        public string $customerType,
        public float $weight,
        public string $state,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->quantity < 0) {
            throw new InvalidQuantityException();
        }

        if ($this->weight < 0) {
            throw new InvalidWeightException();
        }
    }

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
