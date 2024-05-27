<?php

namespace App\UI\Controller\Traits;

use App\UseCase\CreateOrder\CreateOrderCommand;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

trait OrderControllerTrait
{
    private function createOrderFromDecodedJson(array $parameters): ?int
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
