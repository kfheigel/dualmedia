<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Entity\Employee;
use App\Domain\Service\CalculatorInterface;
use App\Infrastructure\Factory\EmployeePayrollFactory;
use App\UseCase\UpdateEmployeeSalary\UpdateEmployeeSalaryCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EmployeePayrollDataCollector
{
    public function __construct(
        private EmployeePayrollFactory $employeePayrollFactory,
        private CalculatorInterface $calculator,
        private MessageBusInterface $messageBus
    ) {
    }

    public function collect(Employee $employee): EmployeePayroll
    {
        $employeePayroll = $this->employeePayrollFactory->createEmployeePayroll($employee);

        if (!$employeePayroll->bonusSalary) {
            $this->calculator->setBonusTypeStrategy($employeePayroll->bonusType->value);
            $employeePayrollWithBonusSalary = $this->calculator->calculate($employeePayroll);

            $command = new UpdateEmployeeSalaryCommand($employee->getSalaryId(), (int)$employeePayrollWithBonusSalary->bonusSalary);
            $this->messageBus->dispatch($command);
        }

        return $employeePayroll;
    }
}
