<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Employee;
use Symfony\Component\Uid\Uuid;

interface EmployeeRepositoryInterface
{
    public function save(Employee $employee): void;
    public function get(Uuid $uuid): Employee;
    public function findOne(Uuid $uuid): ?Employee;
    public function findAll(): array;
}
