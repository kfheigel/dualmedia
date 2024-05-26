<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Orders;
use Symfony\Component\Uid\Uuid;

interface OrderRepositoryInterface
{
    public function save(Orders $orders): void;
    public function get(Uuid $uuid): Orders;
    public function findOne(Uuid $uuid): ?Orders;
    public function findAll(): array;
}
