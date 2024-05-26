<?php

declare(strict_types=1);

namespace App\Domain\Messenger\MessageBus;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function find(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
