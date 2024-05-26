<?php

declare(strict_types=1);

namespace App\ReadModel\Order;

use Symfony\Component\Uid\Uuid;
use App\Domain\Messenger\MessageBus\QueryInterface;
use App\Infrastructure\Validator\OrderId\IsOrderUuidExists;

final class OrderQuery implements QueryInterface
{
    public function __construct(
        #[IsOrderUuidExists(code: 404)]
        public Uuid $orderId
        ) {
    }
}
