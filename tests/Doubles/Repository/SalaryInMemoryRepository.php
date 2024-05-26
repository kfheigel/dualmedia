<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Repository;

use App\Domain\Entity\Salary;
use App\Domain\Repository\NonExistentEntityException;
use App\Domain\Repository\SalaryRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class SalaryInMemoryRepository implements SalaryRepositoryInterface
{
    private array $entities = [];

    public function save(Salary $salary): void
    {
        $this->entities[$salary->getId()->toRfc4122()] = $salary;
    }

    public function get(Uuid $uuid): Salary
    {
        $salary = $this->findOne($uuid);

        if (!$salary) {
            throw new NonExistentEntityException(Salary::class, $uuid->toRfc4122());
        }

        return $salary;
    }

    public function findOne(Uuid $uuid): ?Salary
    {
        return $this->entities[$uuid->toRfc4122()] ?? null;

    }

    public function findAll(): array
    {
        return $this->entities;
    }
}