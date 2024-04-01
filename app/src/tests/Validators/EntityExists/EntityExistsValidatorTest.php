<?php

namespace App\tests\Validators\EntityExists;

use App\Validator\EntityExists\EntityExists;
use App\Validator\EntityExists\EntityExistsValidator;
use App\Validator\TaxNumber\TaxNumber;
use App\Validator\TaxNumber\TaxNumberValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EntityExistsValidatorTest extends ConstraintValidatorTestCase
{
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        parent::setUp();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new EntityExistsValidator($this->entityManager);
    }

    public function testValidateWithWrongConstraint(): void
    {
        $this->expectException(\LogicException::class);
        $this->validator->validate('test', new NotNull());
    }

    public function testValidateValidEntity(): void
    {
        $constraint = new EntityExists('App\Entity\User', 'id');

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'foobar'])
            ->willReturn(new \stdClass());

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with('App\Entity\User')
            ->willReturn($repository);

        $this->validator->validate('foobar', $constraint);
        $this->assertNoViolation();
    }

    /**
     * @dataProvider getEmptyOrNull
     */
    public function testValidateSkipsIfValueEmptyOrNull($value): void
    {
        $constraint = new EntityExists('App\Entity\User', 'id');

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository
            ->expects($this->exactly(0))
            ->method('findOneBy')
            ->with(['id' => $value])
            ->willReturn(new \stdClass());

        $this->entityManager
            ->expects($this->exactly(0))
            ->method('getRepository')
            ->with('App\Entity\User')
            ->willReturn($repository);

        $this->validator->validate($value, $constraint);
        $this->assertNoViolation();
    }

    public function getEmptyOrNull(): \Generator
    {
        yield [''];
        yield [null];
    }

    public function testValidateInvalidEntity(): void
    {
        $constraint = new EntityExists('App\Entity\User', 'id');

        $repository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        $this->validator->validate(1, $constraint);
        $this->assertNotEquals(0, count($this->context->getViolations()));
    }
}
