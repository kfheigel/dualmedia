<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Department;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DepartmentRepository extends ServiceEntityRepository implements DepartmentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $department): void
    {
        $this->getEntityManager()->persist($department);
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
        return $this->find($uuid);
    }

    public function findOneByName(string $name): ?Department
    {
        /** @var Department|null $department */
        $department = $this->findOneBy(["departmentName" => $name]);

        return $department;
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
