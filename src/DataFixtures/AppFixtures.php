<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Для теста класс фикстур один
 */
final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => 'Iphone', 'price' => 100],
            ['name' => 'Headphones', 'price' => 20],
            ['name' => 'Case', 'price' => 10],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $manager->persist($product);
        }

        $coupons = [
            ['code' => 'P10', 'type' => Coupon::TYPE_PERCENT, 'value' => 10],
            ['code' => 'P100', 'type' => Coupon::TYPE_PERCENT, 'value' => 100],
            ['code' => 'D15', 'type' => Coupon::TYPE_FIXED, 'value' => 15],
        ];

        foreach ($coupons as $data) {
            $coupon = new Coupon();
            $coupon->setCode($data['code']);
            $coupon->setType($data['type']);
            $coupon->setValue($data['value']);
            $manager->persist($coupon);
        }

        $manager->flush();
    }
}
