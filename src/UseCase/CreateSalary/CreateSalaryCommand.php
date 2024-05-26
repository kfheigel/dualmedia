<?php

declare(strict_types=1);

namespace App\UseCase\CreateSalary;

use App\Domain\Command\SyncCommandInterface;
use Symfony\Component\Uid\Uuid;

final class CreateSalaryCommand implements SyncCommandInterface
{
    public function __construct(
        public Uuid $salaryId,
        public int $baseSalary
    ) {
    }
}
