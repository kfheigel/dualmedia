<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\Salary;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\Tests\Doubles\Repository\SalaryInMemoryRepository;
use App\Tests\TestTemplate\SalaryRepositoryTestTemplate;

final class SalaryInMemoryRepositoryTest extends SalaryRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();

        $this->salaryRepository = new SalaryInMemoryRepository();
    }

    protected function repository(): SalaryRepositoryInterface
    {
        return $this->salaryRepository;
    }

    protected function save(Salary $salary): void
    {
        $this->salaryRepository->save($salary);
    }
}