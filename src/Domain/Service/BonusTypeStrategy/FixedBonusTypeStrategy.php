<?php

declare(strict_types=1);

namespace App\Domain\Service\BonusTypeStrategy;

use App\Domain\Config\FixedBonusTypeConfig;
use App\Domain\DTO\EmployeePayroll;

final readonly class FixedBonusTypeStrategy implements BonusTypeStrategyInterface
{
    public function __construct(
        private FixedBonusTypeConfig $config
    ) {
    }

    public function calculateBonusAmount(EmployeePayroll $employeePayroll): EmployeePayroll
    {
        $yearsOfEmployment = $this->getYearsOfEmployment($employeePayroll);
        $bonusAmount = $employeePayroll->bonusFactor * $yearsOfEmployment;
        $employeePayroll->bonusSalary = (int)$bonusAmount;

        return $employeePayroll;
    }

    private function getYearsOfEmployment(EmployeePayroll $employeePayroll): int
    {
        $now = date_create('now');
        $employmentDate = $employeePayroll->employmentDate;

        $years = (int)date_diff($employmentDate, $now)->format('%y');

        if ($years > $this->config->yearsOfWorkAccountableToBonus) {
            return $this->config->yearsOfWorkAccountableToBonus;
        }
        return $years;
    }
}
