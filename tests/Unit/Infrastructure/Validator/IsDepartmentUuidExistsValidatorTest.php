<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Validator;

use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Infrastructure\Validator\DepartmentUuid\IsDepartmentUuidExists;
use App\Infrastructure\Validator\DepartmentUuid\IsDepartmentUuidExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use Symfony\Component\Uid\Uuid;

final class IsDepartmentUuidExistsValidatorTest extends ValidatorTestCase
{
    private IsDepartmentUuidExists $givenConstraint;
    protected DepartmentRepositoryInterface $departmentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $departmentRepository = $this->container->get(DepartmentRepositoryInterface::class);
        $this->assertInstanceOf(DepartmentRepositoryInterface::class, $departmentRepository);
        $this->departmentRepository = $departmentRepository;

        $this->givenConstraint = new IsDepartmentUuidExists();
    }

    protected function createValidator(): IsDepartmentUuidExistsValidator
    {
        /** @var IsDepartmentUuidExistsValidator */
        return $this->container->get(IsDepartmentUuidExistsValidator::class);
    }

    /** @test */
    public function department_with_uuid_does_not_exists_already_in_database(): void
    {
        // given
        $givenNonExistingDepartmentId = Uuid::v4();

        // when
        $this->validator->validate($givenNonExistingDepartmentId, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenNonExistingDepartmentId->toRfc4122())
            ->setCode($this->defaultViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function department_uuid_exists_in_database(): void
    {
        // given
        $givenDepartmentId = Uuid::v4();
        $this->giveDepartment($givenDepartmentId, 'Given Name');

        // when
        $this->validator->validate($givenDepartmentId, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validator_sets_validation_code_given_in_constructor(): void
    {
        // given
        $givenNonExistingDepartmentId = Uuid::v4();
        $givenViolationCode = 12345;

        // when
        $constraint = new IsDepartmentUuidExists(code: $givenViolationCode);
        $this->validator->validate($givenNonExistingDepartmentId, $constraint);

        // then
        $this->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $givenNonExistingDepartmentId->toRfc4122())
            ->setCode((string) $givenViolationCode)
            ->assertRaised();
    }
}
