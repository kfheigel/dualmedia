<?php

declare(strict_types=1);

namespace App\EventStorming;

use Symfony\Component\Uid\Uuid;

final class EmployeeCreatedEvent
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public string $surname
    ) {
    }
}
