<?php
declare(strict_types=1);

namespace App\Tax;

use App\Calculator\PriceContext;

class StateTaxStrategy
{
    // Nào tenho certeza nas porcentagens se houveram alteracoes 
    private array $taxes = [
        'AC' => 0.19,
        'AL' => 0.19,
        'AP' => 0.18,
        'AM' => 0.18,
        'BA' => 0.205,
        'CE' => 0.18,
        'DF' => 0.18,
        'ES' => 0.17,
        'GO' => 0.19,
        'MA' => 0.22,
        'MT' => 0.17,
        'MS' => 0.17,
        'MG' => 0.195,
        'PA' => 0.19,
        'PB' => 0.20,
        'PR' => 0.19,
        'PE' => 0.18,
        'PI' => 0.21,
        'RJ' => 0.20,
        'RN' => 0.20,
        'RS' => 0.19,
        'RO' => 0.195,
        'RR' => 0.20,
        'SC' => 0.17,
        'SP' => 0.18,
        'SE' => 0.19,
        'TO' => 0.18,
    ];

    public function apply(float $price, PriceContext $context): float
    {
        $rate = $this->taxes[$context->state] ?? 0;
        return $price * (1 + $rate);
    }
}
