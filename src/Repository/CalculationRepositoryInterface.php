<?php

namespace App\Repository;

use App\Calculator\PriceContext;

interface CalculationRepositoryInterface
{
    public function save(PriceContext $context, float $finalPrice): void;
}