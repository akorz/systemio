<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coupon = new Coupon();
        $coupon->setName('P10');
        $coupon->setDiscount(10);
        $coupon->setIsAbsolute(false);
        $manager->persist($coupon);

        $coupon = new Coupon();
        $coupon->setName('A50');
        $coupon->setDiscount(50);
        $coupon->setIsAbsolute(true);
        $manager->persist($coupon);

        $manager->flush();
    }
}
