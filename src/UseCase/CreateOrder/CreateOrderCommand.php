<?php

namespace App\UseCase\CreateOrder;

use App\Domain\Command\SyncCommandInterface;
use App\Infrastructure\Validator\OrderItems\AreOrderItemsEmpty;

final class CreateOrderCommand implements SyncCommandInterface
{
    public function __construct(
        public string $customerName,
        public string $customerEmail,
        #[AreOrderItemsEmpty(code: 404)]
        public array $orderItems
    ) {
    }
}
