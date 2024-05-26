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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class UnitTestCase extends KernelTestCase
{
    use PrepareRepositoryInMemoryTrait;

    protected ContainerInterface $container;
    protected Generator $faker;
    protected EmployeeRepositoryInterface $employeeRepository;
    protected DepartmentRepositoryInterface $departmentRepository;
    protected SalaryRepositoryInterface $salaryRepository;
    protected ObjectManager $em;
    protected MessageBusInterface $commandBus;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();

        $this->substituteRepositoryInMemoryImplementation();

        $em = $this->container->get(EntityManagerInterface::class);
        Assert::assertInstanceOf(ObjectManager::class, $em);
        $this->em = $em;

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

        $this->faker = Factory::create();
    }

    protected function giveSalary(int $baseSalary): Salary
    {
        return SalaryBuilder::any()
            ->withBaseSalary($baseSalary)
            ->withBonusSalary(null)
            ->build();
    }

    protected function giveDepartment(BonusTypeEnum $givenBonusType, int $givenBonusFactor): Department
    {
        return DepartmentBuilder::any()
            ->withBonusType($givenBonusType)
            ->withBonusFactor($givenBonusFactor)
            ->build();
    }

    protected function giveEmployee(Uuid $givenDepartmentId, Uuid $givenSalaryId, DateTimeInterface $givenEmploymentDate): Employee
    {
        return EmployeeBuilder::any()
            ->withDepartmentId($givenDepartmentId)
            ->withSalaryId($givenSalaryId)
            ->withEmploymentDate($givenEmploymentDate)
            ->build();
    }

    protected function giveEmployeePayroll(Employee $employee, Department $department, Salary $salary): EmployeePayroll
    {
        return new EmployeePayroll(
            $employee->getName(),
            $employee->getSurname(),
            $employee->getEmploymentDate(),
            $department->getDepartmentName(),
            $department->getBonusFactor(),
            $salary->getBaseSalary(),
            $salary->getBonusSalary(),
            $department->getBonusType(),
        );
    }
}
