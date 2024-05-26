<?php

declare(strict_types=1);

namespace App\UseCase\CreateDepartment;

use App\Domain\Command\AsyncCommandInterface;
use App\Infrastructure\Validator\BonusType\IsOneOfBonusTypes;
use App\Infrastructure\Validator\DepartmentName\IsDepartmentNameExists;

final readonly class CreateDepartmentCommand implements AsyncCommandInterface
{
    public function __construct(
        #[IsDepartmentNameExists(code: 400)]
        public string $departmentName,
        #[IsOneOfBonusTypes(code: 404)]
        public string $bonusType,
        public int $bonusFactor,
    ) {
    }
}
