<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\Country;
use App\Exception\CouponNotFoundException;
use App\Exception\InvalidTaxNumberException;
use App\Exception\ProductNotFoundException;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;

/**
 * При расширении купоны и страны разносить по сервисам
*/
final readonly class OrderService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository  $couponRepository,
        private PriceCalculator   $priceCalculator,
    )
    {
    }

    public function calculatePrice(int $productId, string $taxNumber, ?string $couponCode): float
    {
        $product = $this->findProduct($productId);
        $coupon = $this->findCoupon($couponCode);
        $country = $this->getCountry($taxNumber);

        return $this->priceCalculator->calculate($product, $country, $coupon);
    }

    private function findProduct(int $productId): Product
    {
        if (!$product = $this->productRepository->find($productId)) {
            throw new ProductNotFoundException($productId);
        }

        return $product;
    }

    private function findCoupon(?string $couponCode): ?Coupon
    {
        if (!$couponCode) {
            return null;
        }

        if (!$coupon = $this->couponRepository->findByCode($couponCode)) {
            throw new CouponNotFoundException($couponCode);
        }

        return $coupon;
    }

    private function getCountry(string $taxNumber): Country
    {
        if (!$country = Country::fromTaxNumber($taxNumber)) {
            throw new InvalidTaxNumberException($taxNumber);
        }

        return $country;
    }
}
