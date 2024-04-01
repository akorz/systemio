<?php

namespace App\Calculator;

use App\Transformer\TaxNumber;

class TaxCalculator implements CalculatorInterface
{
    public function calculate(float $price, mixed $context): float
    {
        $tax = match (TaxNumber::toCountry($context->taxNumber)) {
            'DE' => 0.19,
            'IT' => 0.22,
            'FR' => 0.20,
            'GR' => 0.24,
            default => 0
        };

        return $price + $tax * $price;
    }
}