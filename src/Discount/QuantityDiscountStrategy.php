<?php
declare(strict_types=1);

namespace App\Discount;

use App\Calculator\PriceContext;

class QuantityDiscountStrategy implements DiscountStrategyInterface
{
    public function apply(float $price, PriceContext $context): float
    {
        return match (true) {
            $context->quantity >= 50 => $price * 0.95,
            $context->quantity >= 10 => $price * 0.97,
            default => $price
        };
    }
}
