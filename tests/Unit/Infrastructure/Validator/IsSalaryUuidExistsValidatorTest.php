<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Validator;

use App\Domain\Repository\SalaryRepositoryInterface;
use App\Infrastructure\Validator\SalaryUuid\IsSalaryUuidExists;
use App\Infrastructure\Validator\SalaryUuid\IsSalaryUuidExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use Symfony\Component\Uid\Uuid;

final class IsSalaryUuidExistsValidatorTest extends ValidatorTestCase
{
    private IsSalaryUuidExists $givenConstraint;
    protected SalaryRepositoryInterface $salaryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $salaryRepository = $this->container->get(SalaryRepositoryInterface::class);
        $this->assertInstanceOf(SalaryRepositoryInterface::class, $salaryRepository);
        $this->salaryRepository = $salaryRepository;

        $this->givenConstraint = new IsSalaryUuidExists();
    }

    protected function createValidator(): IsSalaryUuidExistsValidator
    {
        /** @var IsSalaryUuidExistsValidator */
        return $this->container->get(IsSalaryUuidExistsValidator::class);
    }

    /** @test */
    public function salary_with_uuid_does_not_exists_already_in_database(): void
    {
        // given
        $givenNonExistingSalaryId = Uuid::v4();

        // when
        $this->validator->validate($givenNonExistingSalaryId, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNonExistingSalaryId->toRfc4122())
            ->setCode($this->defaultViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function salary_uuid_exists_in_database(): void
    {
        // given
        $givenSalaryId = Uuid::v4();
        $this->giveSalary($givenSalaryId);

        // when
        $this->validator->validate($givenSalaryId, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validator_sets_validation_code_given_in_constructor(): void
    {
        // given
        $givenNonExistingSalaryId = Uuid::v4();
        $givenViolationCode = 12345;

        // when
        $constraint = new IsSalaryUuidExists(code: $givenViolationCode);
        $this->validator->validate($givenNonExistingSalaryId, $constraint);

        // then
        $this->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $givenNonExistingSalaryId->toRfc4122())
            ->setCode((string) $givenViolationCode)
            ->assertRaised();
    }
}
