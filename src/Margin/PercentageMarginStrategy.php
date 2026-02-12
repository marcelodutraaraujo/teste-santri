<?php
declare(strict_types=1);

namespace App\Margin;

class PercentageMarginStrategy implements MarginStrategyInterface
{
    public function __construct(private float $percentage) {}

    public function apply(float $price): float
    {
        return $price * (1 + $this->percentage / 100);
    }
}
