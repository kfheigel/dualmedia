<?php

declare(strict_types=1);

namespace App\UseCase\CreateSalary;

use App\Domain\Entity\Salary;
use App\Domain\Messenger\CommandHandlerInterface;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\EventStorming\SalaryCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class CreateSalaryHandler implements CommandHandlerInterface
{
    public function __construct(
        private SalaryRepositoryInterface $salaryRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateSalaryCommand $command): void
    {
        $salary = new Salary(
            $command->baseSalary,
            null,
            $command->salaryId
        );
        $this->salaryRepository->save($salary);

        $event = new SalaryCreatedEvent(
            $salary->getId()
        );

        $this->eventBus->dispatch($event);
    }
}
