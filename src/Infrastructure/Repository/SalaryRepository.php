<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Salary;
use App\Domain\Repository\NonExistentEntityException;
use App\Domain\Repository\SalaryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class SalaryRepository extends ServiceEntityRepository implements SalaryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salary::class);
    }

    public function save(Salary $salary): void
    {
        $this->getEntityManager()->persist($salary);
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
        return $this->find($uuid);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
