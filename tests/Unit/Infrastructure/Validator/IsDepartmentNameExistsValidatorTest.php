<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Validator;

use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Infrastructure\Validator\DepartmentName\IsDepartmentNameExists;
use App\Infrastructure\Validator\DepartmentName\IsDepartmentNameExistsValidator;
use App\Tests\Common\ValidatorTestCase;
use Symfony\Component\Uid\Uuid;

final class IsDepartmentNameExistsValidatorTest extends ValidatorTestCase
{
    private IsDepartmentNameExists $givenConstraint;
    protected DepartmentRepositoryInterface $departmentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $departmentRepository = $this->container->get(DepartmentRepositoryInterface::class);
        $this->assertInstanceOf(DepartmentRepositoryInterface::class, $departmentRepository);
        $this->departmentRepository = $departmentRepository;

        $this->givenConstraint = new IsDepartmentNameExists();
    }

    protected function createValidator(): IsDepartmentNameExistsValidator
    {
        /** @var IsDepartmentNameExistsValidator */
        return $this->container->get(IsDepartmentNameExistsValidator::class);
    }

    /** @test */
    public function department_with_name_exists_already_in_database(): void
    {
        // given
        $givenExistingDepartmentName = "Existing department departmentName";
        $this->giveDepartment(Uuid::v4(), $givenExistingDepartmentName);

        // when
        $this->validator->validate($givenExistingDepartmentName, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenExistingDepartmentName)
            ->setCode($this->defaultViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function validator_sets_validation_code_given_in_constructor(): void
    {
        // given
        $givenExistingDepartmentName = 'Existing department departmentName';
        $this->giveDepartment(Uuid::v4(), $givenExistingDepartmentName);
        $givenViolationCode = 12345;

        // when
        $constraint = new IsDepartmentNameExists(code: $givenViolationCode);
        $this->validator->validate($givenExistingDepartmentName, $constraint);

        // then
        $this->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $givenExistingDepartmentName)
            ->setCode((string) $givenViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function department_with_name_does_not_exists_in_database(): void
    {
        // given
        $givenNonExistingDepartmentName = "Brand new department departmentName";

        // when
        $this->validator->validate($givenNonExistingDepartmentName, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }
}
