<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\Tests\Doubles\Repository\DepartmentInMemoryRepository;
use App\Tests\Doubles\Repository\SalaryInMemoryRepository;
use App\Tests\Fixtures\DepartmentBuilder;
use App\Tests\Fixtures\SalaryBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

abstract class ValidatorTestCase extends ConstraintValidatorTestCase
{
    protected ContainerInterface $container;
    protected DepartmentRepositoryInterface $departmentRepository;
    protected SalaryRepositoryInterface $salaryRepository;
    protected string $defaultViolationCode = '400';

    protected function setUp(): void
    {
        $kernelTestCase = new class extends KernelTestCase{
            public static function getContainer(): ContainerInterface {
                return parent::getContainer();
            }
            public static function bootKernel(array $options = []): KernelInterface {
                return parent::bootKernel();
            }
        };

        $kernelTestCase::bootKernel();
        $this->container = $kernelTestCase::getContainer();
        $this->container->set(DepartmentRepositoryInterface::class, new DepartmentInMemoryRepository());
        $this->container->set(SalaryRepositoryInterface::class, new SalaryInMemoryRepository());

        parent::setUp();
    }

    protected function giveDepartment(Uuid $id, string $name): void
    {
        $department = DepartmentBuilder::any()
            ->withId($id)
            ->withName($name)
            ->build();

        $this->departmentRepository->save($department);
    }

    protected function giveSalary(Uuid $id): void
    {
        $salary = SalaryBuilder::any()
            ->withId($id)
            ->build();

        $this->salaryRepository->save($salary);
    }
}
