<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\Country;
use App\Service\PriceCalculator;
use PHPUnit\Framework\TestCase;

final class PriceCalculatorTest extends TestCase
{
    private PriceCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new PriceCalculator();
    }

    // без купона
    public function testCalculatePriceWithoutCoupon(): void
    {
        $product = $this->createProduct();

        $price = $this->calculator->calculate($product, Country::GERMANY);

        // 100 + 19% налог
        $this->assertSame(119.0, $price);
    }

    // фиксированный купон
    public function testCalculatePriceWithFixedCoupon(): void
    {
        $product = $this->createProduct();
        $coupon = $this->createCoupon(Coupon::TYPE_FIXED, 15);

        $price = $this->calculator->calculate($product, Country::GERMANY, $coupon);

        // (100 - 15) + 19%
        $this->assertSame(101.15, $price);
    }

    // процентный купон
    public function testCalculatePriceWithPercentCoupon(): void
    {
        $product = $this->createProduct();
        $coupon = $this->createCoupon(Coupon::TYPE_PERCENT, 6);

        $price = $this->calculator->calculate($product, Country::GREECE, $coupon);

        // (100 - 6%) + 24% = 116.56
        $this->assertSame(116.56, $price);
    }

    private function createProduct(): Product
    {
        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(100);

        return $product;
    }

    private function createCoupon(string $type, int $value): Coupon
    {
        $coupon = new Coupon();
        $coupon->setCode('TEST');
        $coupon->setType($type);
        $coupon->setValue($value);

        return $coupon;
    }
}
