<?php

declare(strict_types=1);

namespace App\Tests\Unit\Doubles;

use App\Domain\Entity\Department;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Tests\Doubles\Repository\DepartmentInMemoryRepository;
use App\Tests\TestTemplate\DepartmentRepositoryTestTemplate;

final class DepartmentInMemoryRepositoryTest extends DepartmentRepositoryTestTemplate
{
    protected function setUp():void
    {
        parent::setUp();

        $this->departmentRepository = new DepartmentInMemoryRepository();
    }

    protected function repository(): DepartmentRepositoryInterface
    {
        return $this->departmentRepository;
    }

    protected function save(Department $department): void
    {
        $this->departmentRepository->save($department);
    }
}