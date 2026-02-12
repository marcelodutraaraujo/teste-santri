<?php 
declare(strict_types=1);

namespace App\Margin;

class FixedMarginStrategy implements MarginStrategyInterface
{
    public function __construct(private float $amount) {}

    public function apply(float $price): float
    {
        return $price + $this->amount;
    }
}
