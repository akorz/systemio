<?php

namespace App\Calculator;

use App\Entity\Coupon;
use App\Repository\CouponRepository;

class CouponCalculator implements CalculatorInterface
{
    public function __construct(protected CouponRepository $couponRepository)
    {
    }

    public function calculate(float $price, mixed $context): float
    {
        $couponCode = $context->couponCode;

        if (null === $couponCode || '' === $couponCode) {
            return $price;
        }

        /** @var Coupon $coupon */
        $coupon = $this->couponRepository->findOneBy(['name' => $couponCode]);

        if (!$coupon) {
            return $price;
        }

        if ($coupon->isAbsolute()) {
            $price = $price - $coupon->getDiscount();

            if ($price < 0)
                return 0;

            return $price;
        }

        return $price - $price * $coupon->getDiscount() / 100;
    }
}