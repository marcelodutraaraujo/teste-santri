<?php 

namespace App\Tax;

use App\Calculator\PriceContext;
use App\Exception\InvalidStateException;

class IcmsTaxStrategy implements TaxStrategyInterface
{
    public function __construct(
        private array $stateRates
    ) {}

    public function apply(float $price, PriceContext $context): float
    {
        if (!isset($this->stateRates[$context->state])) {
            throw new InvalidStateException();
        }

        $rate = $this->stateRates[$context->state];

        return $price * (1 + $rate);
    }
}
