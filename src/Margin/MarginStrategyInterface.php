<?php 
declare(strict_types=1);

namespace App\Margin;

interface MarginStrategyInterface
{
    public function apply(float $price): float;
}
