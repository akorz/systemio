<?php

namespace App\tests\Validators\TaxNumber;

use App\Validator\TaxNumber\TaxNumber;
use App\Validator\TaxNumber\TaxNumberValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new TaxNumberValidator();
    }

    /**
     * @dataProvider provideValidConstraints
     */
    public function testTrueIsValid(?string $value): void
    {
        $this->validator->validate($value, new TaxNumber());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideInvalidConstraints
     */
    public function testTrueIsInvalid(?string $value): void
    {
        $this->validator->validate($value, new TaxNumber());
        $this->buildViolation('The value "{{ value }}" is not valid.')
            ->setParameter('{{ value }}', $value)
            ->assertRaised();
    }

    /**
     * @return \Generator
     */
    public function provideValidConstraints(): \Generator
    {
        $values = [
            'DE123456789',
            'IT12345678901',
            'GR123456789',
            'FRXZ123456789',
        ];

        for ($i = 0; $i < count($values); $i++) {
            yield [$values[$i]];
        }
    }

    public function provideInvalidConstraints(): \Generator
    {
        $values = [
            'DEZ123456789',
            'DEZ1234567890',
            '123DE123456789',
            'DEZ123456789',
            'DEZ1234567890',
            '123DE123456789',
            'IT123456789011',
            'ITZ123456789011',
            'GRZ123456789',
            'GRZ1234567890',
            '123GR123456789',
            'GRZ123456789',
            'GRZ1234567890',
            '123GR123456789',
            'FRXZZ123456789',
            'FRXZZ1234567890',
            '123FRXZ123456789',
            'FRX123456789',
            'FRXZZ123456789',
            'FRXZZ1234567890',
            '123FRXZ123456789FRXZ'
        ];

        for ($i = 0; $i < count($values); $i++) {
            yield [$values[$i]];
        }
    }
}
