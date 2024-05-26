<?php

declare(strict_types=1);

namespace App\UI\Support\FixtureCommand;

use App\Domain\Entity\Salary;
use App\Domain\Repository\SalaryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'support:fixture:salary', description: 'Introduce Salaries')]
final class SeedSalaryCommand extends Command
{
    public function __construct(
        private readonly SalaryRepositoryInterface $salaryRepository,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command create salaries based on provided json export.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $availableSalaries = $this->provideAvailableSalaries();
        foreach ($availableSalaries as $salaryData) {
            $salary = new Salary(
                $salaryData['baseSalary'],
                $salaryData['bonusSalary'],
                Uuid::fromString($salaryData['id'])
            );

            $this->salaryRepository->save($salary);
            $this->em->flush();

            $output->writeln(sprintf("Salary with %s id introduced.", $salary->getId()));
        }

        return Command::SUCCESS;
    }

    private function provideAvailableSalaries(): array
    {
        $salaryJsonFile = __DIR__ . '/../../../../fixtures/salary/salaries.json';
        $jsonContent = file_get_contents($salaryJsonFile);

        if (!$jsonContent) {
            return [];
        }

        $salaries = json_decode($jsonContent, true);
        if (!is_array($salaries)) {
            return [];
        }

        return $salaries;
    }
}
