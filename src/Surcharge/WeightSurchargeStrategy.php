<?php
declare(strict_types=1);

namespace App\Surcharge;

use App\Calculator\PriceContext;
use App\Surcharge\SurchargeStrategyInterface;

class WeightSurchargeStrategy implements SurchargeStrategyInterface
{
    private const SURCHARGE_VALUE = 15.00;

    public function apply(float $price, PriceContext $context): float
    {
        if ( $context->weight > 50 ) {
            return $price + self::SURCHARGE_VALUE;
        }

        return $price;
    }
}
