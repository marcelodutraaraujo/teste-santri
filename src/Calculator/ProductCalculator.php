<?php
declare(strict_types=1);

namespace App\Calculator;

use App\Cache\CacheInterface;
use App\Repository\CalculationRepository;
use App\Discount\DiscountStrategyInterface;
use App\Repository\CalculationRepositoryInterface;
use App\Surcharge\WeightSurchargeStrategy;
use App\Tax\StateTaxStrategy;

class ProductCalculator
{
    public function __construct(
        private array $discounts,
        private WeightSurchargeStrategy $surcharge,
        private StateTaxStrategy $tax,
        private CacheInterface $cache,
        private CalculationRepositoryInterface $repository
    ) {}

    public function calculate(PriceContext $context): float
    {
        //$cacheKey = serialize($context);
        $cacheKey = $context->cacheKey();

        $cached = $this->cache->get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        $price = $context->basePrice * $context->quantity;

        foreach ($this->discounts as $discount) {
            $price = $discount->apply($price, $context);
        }

        $price = $this->surcharge->apply($price, $context);
        $price = $this->tax->apply($price, $context);

        $this->repository->save($context, $price);
        $this->cache->set($cacheKey, $price);

        return $price;
    }
}
