<?php

declare(strict_types=1);

namespace App\EventStorming\Event;

use Symfony\Component\Uid\Uuid;

final class OrderCreatedEvent
{
    public function __construct(
        public Uuid $id,
        public string $customerEmail,
        public string $orderDate,
    ) {
    }
}
