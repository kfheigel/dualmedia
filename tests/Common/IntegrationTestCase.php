<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\DTO\EmployeePayroll;
use App\Domain\Entity\Department;
use App\Domain\Entity\Employee;
use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Domain\Entity\Salary;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\EmployeeRepositoryInterface;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\Infrastructure\Factory\EmployeePayrollFactory;
use App\Tests\Fixtures\DepartmentBuilder;
use App\Tests\Fixtures\EmployeeBuilder;
use App\Tests\Fixtures\SalaryBuilder;
use DateTimeInterface;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class IntegrationTestCase extends KernelTestCase
{
    use PrepareRepositoryInMemoryTrait;

    protected ContainerInterface $container;
    protected EmployeeRepositoryInterface $employeeRepository;
    protected DepartmentRepositoryInterface $departmentRepository;
    protected SalaryRepositoryInterface $salaryRepository;

    protected EmployeePayrollFactory $employeePayrollFactory;
    protected MessageBusInterface $commandBus;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $this->substituteRepositoryInMemoryImplementation();

        $employeeRepository = $this->container->get(EmployeeRepositoryInterface::class);
        Assert::assertInstanceOf(EmployeeRepositoryInterface::class, $employeeRepository);
        $this->employeeRepository = $employeeRepository;

        $departmentRepository = $this->container->get(DepartmentRepositoryInterface::class);
        Assert::assertInstanceOf(DepartmentRepositoryInterface::class, $departmentRepository);
        $this->departmentRepository = $departmentRepository;

        $salaryRepository = $this->container->get(SalaryRepositoryInterface::class);
        Assert::assertInstanceOf(SalaryRepositoryInterface::class, $salaryRepository);
        $this->salaryRepository = $salaryRepository;

        $commandBus = $this->container->get(MessageBusInterface::class);
        Assert::assertInstanceOf(MessageBusInterface::class, $commandBus);
        $this->commandBus = $commandBus;

        $employeePayrollFactory = $this->container->get(EmployeePayrollFactory::class);
        Assert::assertInstanceOf(EmployeePayrollFactory::class, $employeePayrollFactory);
        $this->employeePayrollFactory = $employeePayrollFactory;
    }

    protected function giveSalary(int $baseSalary): Salary
    {
        $salary = SalaryBuilder::any()
            ->withBaseSalary($baseSalary)
            ->withBonusSalary(null)
            ->build();

        $this->salaryRepository->save($salary);

        return $salary;
    }

    protected function giveDepartment(BonusTypeEnum $givenBonusType, int $givenBonusFactor): Department
    {
        $department =  DepartmentBuilder::any()
            ->withBonusType($givenBonusType)
            ->withBonusFactor($givenBonusFactor)
            ->build();

        $this->departmentRepository->save($department);

        return $department;
    }

    protected function giveEmployee(Uuid $givenDepartmentId, Uuid $givenSalaryId, DateTimeInterface $givenEmploymentDate): Employee
    {
        $employee = EmployeeBuilder::any()
            ->withDepartmentId($givenDepartmentId)
            ->withSalaryId($givenSalaryId)
            ->withEmploymentDate($givenEmploymentDate)
            ->build();

        $this->employeeRepository->save($employee);

        return $employee;
    }

    protected function giveEmployeePayroll(Employee $employee): EmployeePayroll
    {
        return $this->employeePayrollFactory->createEmployeePayroll($employee);
    }
}