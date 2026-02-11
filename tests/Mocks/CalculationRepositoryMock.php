<?php
declare(strict_types=1);

namespace Tests\Mocks;

use App\Calculator\PriceContext;
use App\Repository\CalculationRepository;
use App\Repository\CalculationRepositoryInterface;

class CalculationRepositoryMock implements CalculationRepositoryInterface
{
    public array $savedData = [];

    public function save(PriceContext $context, float $finalPrice): void
    {
        $this->savedData[] = [
            'context' => $context,
            'finalPrice' => $finalPrice
        ];
    }
}
