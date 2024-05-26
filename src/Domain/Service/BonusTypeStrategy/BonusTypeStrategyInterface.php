<?php

declare(strict_types=1);

namespace App\Domain\Service\BonusTypeStrategy;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Entity\Salary;

interface BonusTypeStrategyInterface
{
    public function calculateBonusAmount(EmployeePayroll $employeePayroll): EmployeePayroll;
}
