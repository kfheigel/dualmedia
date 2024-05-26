<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\Department;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Symfony\Component\Uid\Uuid;

final class DepartmentInMemoryRepository implements DepartmentRepositoryInterface
{
    private array $entities = [];

    public function save(Department $department): void
    {
        $this->entities[$department->getId()->toRfc4122()] = $department;
    }

    public function get(Uuid $uuid): Department
    {
        $department = $this->findOne($uuid);

        if (!$department) {
            throw new NonExistentEntityException(Department::class, $uuid->toRfc4122());
        }

        return $department;
    }

    public function findOne(Uuid $uuid): ?Department
    {
        return $this->entities[$uuid->toRfc4122()] ?? null;

    }

    public function findOneByName(string $name): ?Department
    {
        foreach ($this->entities as $id => $department) {
            $found = true;

            if ($department->getDepartmentName() !== $name) {
                $found = false;
            }

            if ($found) {
                return $this->entities[$id];
            }
        }
        return null;
    }

    public function findAll(): array
    {
        return $this->entities;
    }
}