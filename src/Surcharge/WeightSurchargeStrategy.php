<?php
declare(strict_types=1);

namespace App\Surcharge;

use App\Calculator\PriceContext;

class WeightSurchargeStrategy
{
    public function apply(float $price, PriceContext $context): float
    {
        return $context->weight > 50 ? $price + 15 : $price;
    }
}
