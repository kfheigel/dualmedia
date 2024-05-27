<?php

declare(strict_types=1);

namespace App\UseCase\CreateOrder;

use App\Domain\Entity\Orders;
use App\Domain\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use App\EventStorming\Event\OrderCreatedEvent;
use App\Domain\Messenger\CommandHandlerInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private OrderRepositoryInterface $orderRepository,
        private MessageBusInterface $eventBus,
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(CreateOrderCommand $command): int
    {
        $orderItems = $command->orderItems;
        $products = $this->prepareProducts($orderItems);

        $orders = new Orders($command->customerName, $command->customerEmail);
        $this->em->persist($orders);

        foreach ($products as $product) {
            $orderItem = new OrderItem($orders, $product['product'], $product['quantity'], $product['product']->getPrice());
            $orderItem->setOrders($orders);
            $this->em->persist($orderItem);
            $this->em->flush();
            $orders->addOrderItem($orderItem);
        }

        $event = new OrderCreatedEvent(
            $orders->getId(),
            $orders->getCustomerEmail(),
            $orders->getOrderDate()->format('Y:m:d H:i:s')
        );

        $this->eventBus->dispatch($event);

        return $orders->getId();
    }

    private function prepareProducts(array $orderItems): array 
    {
        return array_map(function($item) {
            $product = $this->productRepository->findOne((int)$item['id']);
            return [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
        }, $orderItems);
    }
    
}
