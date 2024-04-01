<?php

namespace App\Facade;

interface PaymentMethodInterface
{
    public function process(float $price): void;
}