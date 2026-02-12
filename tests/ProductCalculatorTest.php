<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Calculator\{ProductCalculator, PriceContext};
use App\Discount\{QuantityDiscountStrategy, CustomerDiscountStrategy};
use App\Surcharge\WeightSurchargeStrategy;
use App\Tax\{IcmsTaxStrategy, TaxCalculator};
use App\Margin\{FixedMarginStrategy, PercentageMarginStrategy};
use App\Cache\CacheInterface;
use App\Repository\CalculationRepository;

class ProductCalculatorTest extends TestCase
{
    private function createCalculator(): ProductCalculator
    {
        $cache = $this->createMock(CacheInterface::class);
        $repository = $this->createMock(CalculationRepository::class);

        // Cache sempre vazio no primeiro teste
        $cache->method('get')->willReturn(null);

        $taxCalculator = new TaxCalculator([
            new IcmsTaxStrategy(['SP' => 0.18])
        ]);

        return new ProductCalculator(
            discounts: [
                new QuantityDiscountStrategy(),
                new CustomerDiscountStrategy()
            ],
            surcharge: new WeightSurchargeStrategy(),
            taxCalculator: $taxCalculator,
            margin: new PercentageMarginStrategy(20),
            cache: $cache,
            repository: $repository
        );
    }

    // Teste com margem e imposto e porcentagem de margem
    public function testCalculateWithMarginAndTax(): void
    {
        $calculator = $this->createCalculator();

        $context = new PriceContext(
            basePrice: 100,
            marginType: 'percentage',
            marginValue: 20,
            quantity: 10,
            customerType: 'atacado',
            weight: 10,
            state: 'SP'
        );

        $result = $calculator->calculate($context);

        $this->assertEquals(1236.17, round($result, 2));
    }

     // Teste para valor fixo
    public function testFixedMargin(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $repository = $this->createMock(CalculationRepository::class);

        $cache->method('get')->willReturn(null);

        $taxCalculator = new TaxCalculator([
            new IcmsTaxStrategy(['SP' => 0])
        ]);

        $calculator = new ProductCalculator(
            discounts: [],
            surcharge: new WeightSurchargeStrategy(),
            taxCalculator: $taxCalculator,
            margin: new FixedMarginStrategy(50),
            cache: $cache,
            repository: $repository
        );

        $context = new PriceContext(
            basePrice: 100,
            marginType: 'fixed',
            marginValue: 50,
            quantity: 1,
            customerType: 'varejo',
            weight: 0,
            state: 'SP'
        );

        $result = $calculator->calculate($context);

        $this->assertEquals(150, $result);
    }

    // Teste para verificação de exceção
    public function testInvalidStateThrowsException(): void
    {
        $this->expectException(\App\Exception\InvalidStateException::class);

        $taxCalculator = new TaxCalculator([
            new IcmsTaxStrategy(['SP' => 0.18])
        ]);

        $calculator = new ProductCalculator(
            discounts: [],
            surcharge: new WeightSurchargeStrategy(),
            taxCalculator: $taxCalculator,
            margin: new PercentageMarginStrategy(10),
            cache: $this->createMock(CacheInterface::class),
            repository: $this->createMock(CalculationRepository::class)
        );

        $context = new PriceContext(
            basePrice: 100,
            quantity: 1,
            marginType: 'percentage',
            marginValue: 10,
            customerType: 'regular',
            weight: 0,
            state: 'RJ'
        );

        $calculator->calculate($context);
    }


}
