<?php

namespace App\UI\Controller\Traits;

use Symfony\Component\Uid\Uuid;
use App\UseCase\CreateOrder\CreateOrderCommand;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

trait OrderControllerTrait
{
    private function createOrderFromDecodedJson(array $parameters): ?Uuid
    {
        $customerName = $parameters['customerName'];
        $customerEmail = $parameters['customerEmail'];
        $orderItems = $parameters['orderItems'];

        try {
            $employeeCommand = new CreateOrderCommand(
                $customerName,
                $customerEmail,
                $orderItems
            );
            $envelope = $this->commandBus->dispatch($employeeCommand);

            $handledStamp = $envelope->last(HandledStamp::class);
            $orderId = $handledStamp->getResult();
            return $orderId;
            
        } catch (ValidationFailedException) {
            return null;
        }
    }
}
