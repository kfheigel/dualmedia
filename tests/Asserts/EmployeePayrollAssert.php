<?php

declare(strict_types=1);

namespace App\Tests\Asserts;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Entity\Department;
use App\Domain\Entity\Employee;
use App\Domain\Entity\Salary;
use PHPUnit\Framework\Assert;

final class EmployeePayrollAssert
{
    public static function hasTheSameProperties(
        Employee $givenEmployee,
        Department $givenDepartment,
        Salary $givenSalary,
        EmployeePayroll $employeePayroll
    ): void
    {
        Assert::assertEquals($givenEmployee->getName(), $employeePayroll->name);
        Assert::assertEquals($givenEmployee->getSurname(), $employeePayroll->surname);
        Assert::assertEquals($givenEmployee->getEmploymentDate(), $employeePayroll->employmentDate);
        Assert::assertEquals($givenDepartment->getDepartmentName(), $employeePayroll->departmentName);
        Assert::assertEquals($givenDepartment->getBonusType(), $employeePayroll->bonusType);
        Assert::assertEquals($givenDepartment->getBonusFactor(), $employeePayroll->bonusFactor);
        Assert::assertEquals($givenSalary->getBaseSalary(), $employeePayroll->baseSalary);
    }
}