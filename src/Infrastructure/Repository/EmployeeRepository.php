<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Employee;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Repository\EmployeeRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

final class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee): void
    {
        $this->getEntityManager()->persist($employee);
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
        return $this->find($uuid);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
