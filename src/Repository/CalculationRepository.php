<?php
declare(strict_types=1);

namespace App\Repository;

use PDO;
use App\Calculator\PriceContext;
use App\Repository\CalculationRepositoryInterface;

class CalculationRepository implements CalculationRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function save(PriceContext $context, float $finalPrice): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO price_calculations 
            (base_price, margin_type, margin_value, quantity, customer_type, weight, state, final_price, created_at)
            VALUES (:base_price, :margin_type, :margin_value, :quantity, :customer_type, :weight, :state, :final_price, NOW())'
        );

        $stmt->execute([
            'base_price' => $context->basePrice,
            'margin_type' => $context->marginType,
            'margin_value' => $context->marginValue,
            'quantity' => $context->quantity,
            'customer_type' => $context->customerType,
            'weight' => $context->weight,
            'state' => $context->state,
            'final_price' => $finalPrice
        ]);
    }
}
