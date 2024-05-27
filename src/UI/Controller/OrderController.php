<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\ReadModel\Order\OrderQuery;
use App\Domain\Messenger\MessageBus\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\UI\Controller\Traits\OrderControllerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class OrderController extends AbstractController
{
    use OrderControllerTrait;

    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private SerializerInterface $serializer
    ) {
    }
    
    #[Route('/order', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $response = $this->createOrderFromDecodedJson($data);
        if(!$response) {
            return new JsonResponse('', Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/order/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(QueryBus $queryBus, string $id): JsonResponse
    {
        $query = new OrderQuery((int)$id);
        $order = $queryBus->find($query);
        $jsonData = [
            'id' => $order->getId(),
            'orderDate' => $order->getOrderDate()->format('Y-m-d H:i:s'),
            'customerName' => $order->getCustomerName(),
            'customerEmail' => $order->getCustomerEmail(),
        ];

        return new JsonResponse($jsonData, Response::HTTP_OK);
    }
}