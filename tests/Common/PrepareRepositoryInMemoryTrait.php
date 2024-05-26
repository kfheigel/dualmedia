<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\EmployeeRepositoryInterface;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\Tests\Doubles\Repository\DepartmentInMemoryRepository;
use App\Tests\Doubles\Repository\EmployeeInMemoryRepository;
use App\Tests\Doubles\Repository\SalaryInMemoryRepository;

trait PrepareRepositoryInMemoryTrait
{
    private function substituteRepositoryInMemoryImplementation(): void
    {
        $this->container->set(EmployeeRepositoryInterface::class, new EmployeeInMemoryRepository());
        $this->container->set(DepartmentRepositoryInterface::class, new DepartmentInMemoryRepository());
        $this->container->set(SalaryRepositoryInterface::class, new SalaryInMemoryRepository());
    }
}