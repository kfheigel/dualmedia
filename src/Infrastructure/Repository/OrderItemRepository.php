<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Repository\OrderItemRepositoryInterface;
use App\Domain\Entity\OrderItem;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

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

    public function get(Uuid $uuid): OrderItem
    {
        $orderItem = $this->findOne($uuid);

        if (!$orderItem) {
            throw new NonExistentEntityException(OrderItem::class, $uuid->toRfc4122());
        }

        return $orderItem;
    }

    public function findOne(Uuid $uuid): ?OrderItem
    {
        return $this->find($uuid);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
