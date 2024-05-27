<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Orders;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function save(Orders $orders): void
    {
        $this->getEntityManager()->persist($orders);
    }

    public function get(int $id): Orders
    {
        $order = $this->findOne($id);

        if (!$order) {
            throw new NonExistentEntityException(Orders::class, (string)$id);
        }

        return $order;
    }

    public function findOne(int $id): ?Orders
    {
        return $this->createQueryBuilder('o')
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
