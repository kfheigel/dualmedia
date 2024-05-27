<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Orders;

interface OrderRepositoryInterface
{
    public function save(Orders $orders): void;
    public function get(int $id): Orders;
    public function findOne(int $id): ?Orders;
    public function findAll(): array;
}
