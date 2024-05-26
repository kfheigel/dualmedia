<?php

declare(strict_types=1);

namespace App\UseCase\CreateDepartment;

use App\Domain\Entity\Department;
use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Domain\Messenger\CommandHandlerInterface;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\EventStorming\DepartmentCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class CreateDepartmentHandler implements CommandHandlerInterface
{
    public function __construct(
        private DepartmentRepositoryInterface $departmentRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CreateDepartmentCommand $command): void
    {
        $department = new Department(
            $command->departmentName,
            BonusTypeEnum::from($command->bonusType),
            $command->bonusFactor,
        );
        $this->departmentRepository->save($department);

        $event = new DepartmentCreatedEvent(
            $department->getId(),
            $department->getDepartmentName(),
        );

        $this->eventBus->dispatch($event);
    }
}
