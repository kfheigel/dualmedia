<?php

declare(strict_types=1);

namespace App\ReadModel\Order;

use App\Domain\Entity\Orders;
use App\Domain\Messenger\QueryFinderInterface;
use App\Domain\Repository\OrderRepositoryInterface;

final class OrderFinder implements QueryFinderInterface
{
    public function __construct(
        private OrderRepositoryInterface $repository,
    ) {
    }

    public function __invoke(OrderQuery $query): Orders
    {
        return $this->repository->findOne($query->orderId);
    }
}
