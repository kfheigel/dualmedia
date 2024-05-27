<?php

declare(strict_types=1);

namespace App\ReadModel\Order;

use App\Domain\Messenger\MessageBus\QueryInterface;
use App\Infrastructure\Validator\OrderId\IsOrderIdExists;

final class OrderQuery implements QueryInterface
{
    public function __construct(
        #[IsOrderIdExists(code: 404)]
        public int $orderId
        ) {
    }
}
