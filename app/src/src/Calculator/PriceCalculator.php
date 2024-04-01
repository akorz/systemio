<?php

namespace App\Calculator;

use App\Transformer\TaxNumber;

class PriceCalculator implements CalculatorInterface
{
    public function __construct(protected array $chainedCalculators)
    {
    }

    public function calculate(float $price, mixed $context): float
    {
        /** @var CalculatorInterface $calculator */
        foreach ($this->chainedCalculators as $calculator) {
            $price = $calculator->calculate($price, $context);
        }

        return $price;
    }
}