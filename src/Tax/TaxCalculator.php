<?php 

namespace App\Tax;

use App\Calculator\PriceContext;

class TaxCalculator
{
    public function __construct(
        private array $taxStrategies
    ) {}

    public function apply(float $price, PriceContext $context): float
    {
        foreach ($this->taxStrategies as $tax) {
            $price = $tax->apply($price, $context);
        }

        return $price;
    }
}
