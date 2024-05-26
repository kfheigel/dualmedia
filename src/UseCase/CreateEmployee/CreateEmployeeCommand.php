<?php

declare(strict_types=1);

namespace App\UseCase\CreateEmployee;

use App\Domain\Command\AsyncCommandInterface;
use App\Infrastructure\Validator\DepartmentUuid\IsDepartmentUuidExists;
use App\Infrastructure\Validator\SalaryUuid\IsSalaryUuidExists;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

final readonly class CreateEmployeeCommand implements AsyncCommandInterface
{
    public function __construct(
        public string $name,
        public string $surname,
        #[IsDepartmentUuidExists(code: 404)]
        public Uuid $departmentId,
        #[IsSalaryUuidExists(code: 404)]
        public Uuid $salaryId,
        public DateTimeInterface $employmentDate
    ) {
    }
}
