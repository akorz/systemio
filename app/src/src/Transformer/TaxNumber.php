<?php

namespace App\Transformer;

class TaxNumber
{
    static public function toCountry(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }
}