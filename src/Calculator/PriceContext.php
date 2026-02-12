<?php
declare(strict_types=1);

namespace App\Calculator;

use App\Exception\InvalidWeightException;
use App\Exception\InvalidQuantityException;
use App\Exception\InvalidMarginTypeException;
use App\Exception\InvalidMarginValueException;
use App\Exception\InvalidBasePriceException;

class PriceContext
{
    public function __construct(
        public float $basePrice,
        public string $marginType,
        public float $marginValue,
        public int $quantity,
        public string $customerType,
        public float $weight,
        public string $state,
    ) {
        $this->validate(); 
    }

    private function validate(): void
    {
        if ($this->basePrice < 0) {
            throw new InvalidBasePriceException();
        }

        if ($this->quantity < 0) {
            throw new InvalidQuantityException();
        }

        if ($this->weight < 0) {
            throw new InvalidWeightException();
        }

        if ($this->marginType !== 'fixed' && $this->marginType !== 'percentage') {
            throw new InvalidMarginTypeException();
        }

        if ($this->marginValue < 0) {
            throw new InvalidMarginValueException();
        }
    }

    public function cacheKey(): string
    {
        return md5(json_encode([
            $this->basePrice,
            $this->marginType,
            $this->marginValue,
            $this->quantity,
            $this->customerType,
            $this->weight,
            $this->state
        ]));
    }
}
