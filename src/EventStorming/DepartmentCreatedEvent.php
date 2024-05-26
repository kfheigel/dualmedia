<?php

declare(strict_types=1);

namespace App\EventStorming;

use Symfony\Component\Uid\Uuid;

final class DepartmentCreatedEvent
{
    public function __construct(
        public Uuid $id,
        public string $departmentName
    ) {
    }
}
