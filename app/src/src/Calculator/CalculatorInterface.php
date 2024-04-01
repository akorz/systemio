<?php

namespace App\Calculator;

interface CalculatorInterface
{
    public function calculate(float $price, mixed $context): float;
}