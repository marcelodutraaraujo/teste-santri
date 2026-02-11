<?php
declare(strict_types=1);

namespace App\Surcharge;

use App\Calculator\PriceContext;

interface SurchargeStrategyInterface
{
    public function apply(float $price, PriceContext $context): float;
}