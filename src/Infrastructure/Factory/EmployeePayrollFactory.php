<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Entity\Employee;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\SalaryRepositoryInterface;

final readonly class EmployeePayrollFactory
{
    public function __construct(
        private SalaryRepositoryInterface $salaryRepository,
        private DepartmentRepositoryInterface $departmentRepository,
    ) {
    }

    public function createEmployeePayroll(Employee $employee): EmployeePayroll
    {
        $department = $this->departmentRepository->get($employee->getDepartmentId());
        $salary = $this->salaryRepository->get($employee->getSalaryId());

        return new EmployeePayroll(
            $employee->getName(),
            $employee->getSurname(),
            $employee->getEmploymentDate(),
            $department->getDepartmentName(),
            $department->getBonusFactor(),
            $salary->getBaseSalary(),
            $salary->getBonusSalary(),
            $department->getBonusType(),
        );
    }
}
