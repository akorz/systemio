<?php

namespace App\Validator\TaxNumber;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var TaxNumber $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match('/\A((DE|GR|FR[a-zA-Z]{2})\d{9}|(IT\d{11}))\z/', $value, $matches)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
