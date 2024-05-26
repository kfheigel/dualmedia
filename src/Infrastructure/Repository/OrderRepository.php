<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Orders;
use Symfony\Component\Uid\Uuid;
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

    public function get(Uuid $uuid): Orders
    {
        $order = $this->findOne($uuid);

        if (!$order) {
            throw new NonExistentEntityException(Orders::class, $uuid->toRfc4122());
        }

        return $order;
    }

    public function findOne(Uuid $uuid): ?Orders
    {
        return $this->find($uuid);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
