<?php

declare(strict_types=1);

namespace App\UI\Support\FixtureCommand;

use App\Domain\Entity\Employee;
use App\Domain\Repository\EmployeeRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'support:fixture:employee', description: 'Introduce Employees')]
final class SeedEmployeeCommand extends Command
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command create employees based on provided json export.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $availableEmployees = $this->provideAvailableEmployees();
        foreach ($availableEmployees as $employeeData) {
            /** @var DateTimeImmutable $employeeDate */
            $employeeDate = (new DateTimeImmutable())->createFromFormat('Y-m-d', $employeeData['employmentDate']);

            $employee = new Employee(
                $employeeData['name'],
                $employeeData['surname'],
                Uuid::fromString($employeeData['departmentId']),
                Uuid::fromString($employeeData['salaryId']),
                $employeeDate,
                Uuid::fromString($employeeData['id'])
            );

            $this->employeeRepository->save($employee);
            $this->em->flush();

            $output->writeln(sprintf("Employee: %s %s introduced", $employee->getName(), $employee->getSurname()));
        }

        return Command::SUCCESS;
    }

    private function provideAvailableEmployees(): array
    {
        $employeeJsonFile = __DIR__ . '/../../../../fixtures/employee/employees.json';
        $jsonContent = file_get_contents($employeeJsonFile);

        if (!$jsonContent) {
            return [];
        }

        $employees = json_decode($jsonContent, true);
        if (!is_array($employees)) {
            return [];
        }

        return $employees;
    }
}
