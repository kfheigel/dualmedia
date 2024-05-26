<?php

declare(strict_types=1);

namespace App\UseCase\UpdateEmployeeSalary;

use App\Domain\Entity\Salary;
use App\Domain\Messenger\CommandHandlerInterface;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\EventStorming\SalaryUpdatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class UpdateEmployeeSalaryHandler implements CommandHandlerInterface
{
    public function __construct(
        private SalaryRepositoryInterface $salaryRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(UpdateEmployeeSalaryCommand $command): void
    {
        /** @var Salary $salary */
        $salary = $this->salaryRepository->findOne($command->id);
        $salary->setBonusSalary($command->bonusSalary);
        $this->salaryRepository->save($salary);

        $event = new SalaryUpdatedEvent(
            $salary->getId(),
        );

        $this->eventBus->dispatch($event);
    }
}
