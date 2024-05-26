<?php

namespace App\Domain\Repository;

use Symfony\Component\Uid\Uuid;
use App\Domain\Entity\OrderItem;

interface OrderItemRepositoryInterface
{
    public function save(OrderItem $orderItem): void;
    public function get(Uuid $uuid): OrderItem;
    public function findOne(Uuid $uuid): ?OrderItem;
    public function findAll(): array;
}
