<?php
declare(strict_types=1);

namespace App\Discount;

use App\Calculator\PriceContext;

class CustomerDiscountStrategy implements DiscountStrategyInterface
{
    public function apply(float $price, PriceContext $context): float
    {
        if ($context->customerType === 'varejo') {
            return $price * 0.98;
        }
        if ($context->customerType === 'atacado') {
            return $price * 0.90;
        }
        if ($context->customerType === 'revendedor') {
            return $price * 0.80;
        }

        http_response_code(400);
        echo json_encode(['error' => 'Tipo de cliente inválido, verifique a documentação sobre os tipos de clientes']);
        exit;
        //return $price;
    }
}
