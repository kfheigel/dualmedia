<?php

declare(strict_types=1);

namespace App\Domain\Service\BonusTypeStrategy;

use App\Domain\DTO\EmployeePayroll;

final readonly class PercentageBonusTypeStrategy implements BonusTypeStrategyInterface
{
    public function calculateBonusAmount(EmployeePayroll $employeePayroll): EmployeePayroll
    {
        $percentageBonusFactor = $employeePayroll->bonusFactor / 100;
        $baseSalary = $employeePayroll->baseSalary;
        $bonusAmount = $baseSalary * $percentageBonusFactor;
        $employeePayroll->bonusSalary = (int)$bonusAmount;

        return $employeePayroll;
    }
}
