<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Service\BonusTypeStrategy\BonusTypeStrategyInterface;
use App\Kernel;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class BonusSalaryCalculator implements CalculatorInterface
{
    private BonusTypeStrategyInterface $strategy;
    private readonly ContainerInterface $container;
    public function __construct(
        Kernel $kernel
    ) {
        $this->container = $kernel->getContainer();
    }

    public function calculate(EmployeePayroll $employeePayroll): EmployeePayroll
    {
        $updatedEmployeePayroll = $this->strategy->calculateBonusAmount($employeePayroll);
        if (!is_int($updatedEmployeePayroll->bonusSalary)) {
            throw new InvalidArgumentException('Bonus salary was not calculated');
        }

        return $updatedEmployeePayroll;
    }

    public function setBonusTypeStrategy(string $strategy): void
    {
        $strategyClassFQCN = sprintf('App\Domain\Service\BonusTypeStrategy\%sBonusTypeStrategy', ucfirst($strategy));

        /** @var BonusTypeStrategyInterface $strategyInstance */
        $strategyInstance = $this->container->get($strategyClassFQCN);

        $this->strategy = $strategyInstance;
    }
}
