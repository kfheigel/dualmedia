<?php

declare(strict_types=1);

namespace App\EventStorming\Event;


final class OrderCreatedEvent
{
    public function __construct(
        public int $id,
        public string $customerEmail,
        public string $orderDate,
    ) {
    }
}
