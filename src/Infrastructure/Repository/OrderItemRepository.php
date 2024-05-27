<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Repository\OrderItemRepositoryInterface;
use App\Domain\Entity\OrderItem;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderItemRepository extends ServiceEntityRepository implements OrderItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    public function save(OrderItem $orderItem): void
    {
        $this->getEntityManager()->persist($orderItem);
    }

    public function get(int $id): OrderItem
    {
        $order = $this->findOne($id);

        if (!$order) {
            throw new NonExistentEntityException(OrderItem::class, (string)$id);
        }

        return $order;
    }

    public function findOne(int $id): ?OrderItem
    {
        return $this->createQueryBuilder('oi')
            ->where('oi.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
