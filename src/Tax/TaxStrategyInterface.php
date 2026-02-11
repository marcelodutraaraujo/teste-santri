<?php

namespace App\Tax;

use App\Calculator\PriceContext;

interface TaxStrategyInterface
{
    public function apply(float $price, PriceContext $context): float;
}