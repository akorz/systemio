<?php

namespace App\Factory;

use App\Facade\PaymentMethodInterface;
use App\Facade\PaypalPaymentMethod;
use App\Facade\StripePaymentMethod;

class PaymentFactory
{
    public static function getPaymentMethod(string $processor): PaymentMethodInterface
    {
        switch ($processor) {
            case "paypal":
                return new PaypalPaymentMethod();
            case "stripe":
                return new StripePaymentMethod();
            default:
                throw new \Exception("Unknown Payment Method");
        }
    }
}