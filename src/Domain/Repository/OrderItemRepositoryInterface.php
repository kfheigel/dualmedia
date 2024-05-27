<?php

namespace App\Domain\Repository;

use App\Domain\Entity\OrderItem;

interface OrderItemRepositoryInterface
{
    public function save(OrderItem $orderItem): void;
    public function get(int $id): OrderItem;
    public function findOne(int $id): ?OrderItem;
    public function findAll(): array;
}
