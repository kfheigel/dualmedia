<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\Employee;
use App\Domain\Repository\EmployeeRepositoryInterface;
use App\Tests\TestTemplate\EmployeeRepositoryTestTemplate;
use App\Tests\Doubles\Repository\EmployeeInMemoryRepository;

final class EmployeeInMemoryRepositoryTest extends EmployeeRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();

        $this->employeeRepository = new EmployeeInMemoryRepository();
    }

    protected function repository(): EmployeeRepositoryInterface
    {
        return $this->employeeRepository;
    }

    protected function save(Employee $employee): void
    {
        $this->employeeRepository->save($employee);
    }
}
