<?php

declare(strict_types=1);

namespace App\UI\Controller;

use Symfony\Component\Uid\Uuid;
use App\ReadModel\Order\OrderQuery;
use App\Domain\Messenger\MessageBus\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\UI\Controller\Traits\OrderControllerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    use OrderControllerTrait;

    public function __construct(
        private readonly MessageBusInterface $commandBus
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

        return new JsonResponse($response->toRfc4122(), Response::HTTP_OK);
    }

    #[Route('/order/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(QueryBus $queryBus, string $id): JsonResponse
    {
        $uuid = Uuid::fromString($id);
        $query = new OrderQuery($uuid);
        $data = $queryBus->find($query);

        return $this->json($data);
    }
}