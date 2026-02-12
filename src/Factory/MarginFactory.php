<?php
declare(strict_types=1);

namespace App\Factory;

use App\Margin\{PercentageMarginStrategy, FixedMarginStrategy, MarginStrategyInterface};
use InvalidArgumentException;

class MarginFactory
{
    public static function create(string $type, float $value): MarginStrategyInterface
    {
        return match ($type) {
            'percentage' => new PercentageMarginStrategy($value),
            'fixed' => new FixedMarginStrategy($value),
            default => throw new InvalidArgumentException('Tipo de margem inválido'),
        };
    }
}
