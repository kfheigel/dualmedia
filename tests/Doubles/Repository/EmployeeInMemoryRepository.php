<?php

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\Employee;
use Symfony\Component\Uid\Uuid;
use App\Domain\Repository\EmployeeRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;

final class EmployeeInMemoryRepository implements EmployeeRepositoryInterface
{
    private array $entities = [];

    public function save(Employee $employee): void
    {
        $this->entities[$employee->getId()->toRfc4122()] = $employee;
    }

    public function get(Uuid $uuid): Employee
    {
        $employee = $this->findOne($uuid);
        
        if (!$employee) {
            throw new NonExistentEntityException(Employee::class, $uuid->toRfc4122());
        }

        return $employee;
    }

    public function findOne(Uuid $uuid): ?Employee
    {
        return $this->entities[$uuid->toRfc4122()] ?? null;

    }

    public function findAll(): array
    {
        return $this->entities;
    }
}
