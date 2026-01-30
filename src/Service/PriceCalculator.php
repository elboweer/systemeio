<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\Country;

final class PriceCalculator
{
    /**
     * Рассчитывает итоговую цену с учётом купона и налога
     */
    public function calculate(Product $product, Country $country, ?Coupon $coupon = null): float
    {
        $price = (float)$product->getPrice();

        // Скидка по купону
        if ($coupon !== null) {
            $price = $this->applyDiscount($price, $coupon);
        }

        // На всякий случай, если цена вдруг окажется меньше
        $price = max(0, $price);

        // Налог
        $tax = $price * $country->getTaxRate() / 100;
        $price = $price + $tax;

        return round($price, 2);
    }

    private function applyDiscount(float $price, Coupon $coupon): float
    {
        if ($coupon->isFixedDiscount()) {
            return $price - $coupon->getValue();
        }

        if ($coupon->isPercentDiscount()) {
            $discount = $price * ($coupon->getValue() / 100);
            return $price - $discount;
        }

        return $price;
    }
}
