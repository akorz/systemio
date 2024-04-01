<?php

namespace App\Facade;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentMethod extends StripePaymentProcessor implements PaymentMethodInterface
{
    public function process(float $price): void
    {
        if (!$this->processPayment($price))
            throw new \Exception('Error description');
    }
}