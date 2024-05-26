<?php

declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\Entity\Enum\BonusTypeEnum;
use DateTimeInterface;

final class EmployeePayroll
{
    public function __construct(
        public string $name,
        public string $surname,
        public DateTimeInterface $employmentDate,
        public string $departmentName,
        public int $bonusFactor,
        public int $baseSalary,
        public ?int $bonusSalary,
        public BonusTypeEnum $bonusType,
    ) {
    }
}
