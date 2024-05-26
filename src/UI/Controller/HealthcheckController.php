<?php

declare(strict_types=1);

namespace App\UI\Controller;

use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\Connection;

final class HealthcheckController extends AbstractController
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly string $rabbitDsn
    ) {
    }

    #[Route('/healthcheck', name: 'app_healthcheck', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $status = Response::HTTP_OK;
        $checks = [
            'http' => 'ok',
            'database' => 'ok',
            'rabbitmq' => 'ok'
        ];

        try {
            $this->employeeRepository->findAll();
        } catch (Exception) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $checks['database'] = 'not ok';
        }

        try {
            $connection = Connection::fromDsn($this->rabbitDsn);
            if (!$connection->channel()->isConnected()) {
                throw new RuntimeException("RabbitMQ failed connection");
            }
        } catch (Exception) {
            $checks['rabbitmq'] = 'not ok';
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($checks, $status);
    }
}
