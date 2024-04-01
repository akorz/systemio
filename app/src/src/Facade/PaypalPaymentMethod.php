<?php

namespace App\Facade;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Exception;

class PaypalPaymentMethod extends PaypalPaymentProcessor implements PaymentMethodInterface
{
    public function process(float $price): void
    {
        $this->pay($price * 100);
    }
}