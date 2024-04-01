<?php

namespace App\DTO;

use App\Validator\EntityExists\EntityExists;
use App\Validator\TaxNumber\TaxNumber;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CalculateDTO
{
    public function __construct(
        #[NotBlank]
        #[Type('int')]
        #[EntityExists(entity: 'App\Entity\Product', property: 'id')]
        public readonly int     $product,

        #[NotBlank]
        #[TaxNumber]
        #[Type('string')]
        public readonly string  $taxNumber,

        #[Type('string')]
        #[EntityExists(entity: 'App\Entity\Coupon', property: 'name')]
        public readonly ?string $couponCode,
    ) {

    }
}