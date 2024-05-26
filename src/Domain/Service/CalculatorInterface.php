<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Entity\Salary;

interface CalculatorInterface
{
    public function calculate(EmployeePayroll $employeePayroll): EmployeePayroll;
    public function setBonusTypeStrategy(string $strategy): void;
}
