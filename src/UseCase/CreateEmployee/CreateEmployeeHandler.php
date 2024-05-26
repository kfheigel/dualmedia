<?php

declare(strict_types=1);

namespace App\UseCase\CreateEmployee;

use App\Domain\Entity\Employee;
use App\EventStorming\EmployeeCreatedEvent;
use App\Domain\Messenger\CommandHandlerInterface;
use App\Domain\Repository\EmployeeRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateEmployeeHandler implements CommandHandlerInterface
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateEmployeeCommand $command): void
    {
        $employee = new Employee(
            $command->name,
            $command->surname,
            $command->departmentId,
            $command->salaryId,
            $command->employmentDate,
        );
        $this->employeeRepository->save($employee);

        $event = new EmployeeCreatedEvent(
            $employee->getId(),
            $employee->getName(),
            $employee->getSurname()
        );

        $this->eventBus->dispatch($event);
    }
}
