<?php
declare(strict_types=1);

namespace App\Discount;

use App\Calculator\PriceContext;

interface DiscountStrategyInterface
{
    public function apply(float $price, PriceContext $context): float;
}
