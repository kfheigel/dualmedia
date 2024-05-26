<?php

declare(strict_types=1);

namespace App\Infrastructure\MessageBus\Stamp;

use Symfony\Component\Clock\Clock;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class TimeStamp implements StampInterface
{
    private Clock $dateTime;

    public function __construct()
    {
        $this->dateTime = new Clock();
    }

    public function __toString(): string
    {
        $now = $this->dateTime->now();

        return $now->format('Y-M-d H:i:s');
    }
}
