<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Calculator\{ProductCalculator, PriceContext};
use App\Discount\{QuantityDiscountStrategy, CustomerDiscountStrategy};
use App\Surcharge\WeightSurchargeStrategy;
use App\Tax\StateTaxStrategy;
use Tests\Mocks\{FakeCache, CalculationRepositoryMock}; 

class ProductCalculatorTest extends TestCase
{
    public function testCalculateWithVarejoCustomerAndWeight(): void
    {
        $repository = new CalculationRepositoryMock();
        $cache = new FakeCache();

        $calculator = new ProductCalculator(
            [
                new QuantityDiscountStrategy(),
                new CustomerDiscountStrategy()
            ],
            new WeightSurchargeStrategy(),
            new StateTaxStrategy(),
            $cache,
            $repository
        );

        $context = new PriceContext(
            basePrice: 30,
            quantity: 100,
            customerType: 'varejo',
            weight: 60,
            state: 'SP'
        );

        $finalPrice = $calculator->calculate($context);

        $this->assertGreaterThan(0, $finalPrice);
        $this->assertCount(1, $repository->savedData); 
    }

    public function testCacheIsUsed(): void
    {
        $repository = new CalculationRepositoryMock();
        $cache = new FakeCache();

        $calculator = new ProductCalculator(
            [new QuantityDiscountStrategy()],
            new WeightSurchargeStrategy(),
            new StateTaxStrategy(),
            $cache,
            $repository
        );

        $context = new PriceContext(100, 1, 'varejo', 10, 'PI');

        $calculator->calculate($context);
        $calculator->calculate($context);

        $this->assertCount(1, $repository->savedData);
    }
}
