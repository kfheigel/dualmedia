<?php

declare(strict_types=1);

namespace App\UI\Support\FixtureCommand;

use App\Domain\Entity\Department;
use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Domain\Repository\DepartmentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'support:fixture:department', description: 'Introduce Departments')]
final class SeedDepartmentCommand extends Command
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command create departments based on provided json export.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $availableDepartments = $this->provideAvailableDepartments();
        foreach ($availableDepartments as $departmentData) {
            $department = new Department(
                $departmentData['departmentName'],
                BonusTypeEnum::from($departmentData['bonusType']),
                $departmentData['bonusFactor'],
                Uuid::fromString($departmentData['id'])
            );

            $this->departmentRepository->save($department);
            $this->em->flush();

            $output->writeln(sprintf("%s Department introduced", $department->getDepartmentName()));
        }

        return Command::SUCCESS;
    }

    private function provideAvailableDepartments(): array
    {
        $departmentJsonFile = __DIR__ . '/../../../../fixtures/department/departments.json';
        $jsonContent = file_get_contents($departmentJsonFile);

        if (!$jsonContent) {
            return [];
        }

        $departments = json_decode($jsonContent, true);
        if (!is_array($departments)) {
            return [];
        }

        return $departments;
    }
}
