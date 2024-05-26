<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Employee;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Common\UnitTestCase;
use Symfony\Component\Uid\Uuid;
use App\Tests\Fixtures\EmployeeBuilder;
use App\Domain\Repository\EmployeeRepositoryInterface;

abstract class EmployeeRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): EmployeeRepositoryInterface;
    abstract protected function save(Employee $employee): void;

    /** @test */
    public function add_and_find_one_by_id(): void
    {
        // given
        $givenId = '809e6b49-49de-45e2-8592-53fc0b957602';
        $givenEmployee = EmployeeBuilder::any()
            ->withId(Uuid::fromString($givenId))
            ->build();

        // when
        $this->save($givenEmployee);
        $employee = $this->repository()->findOne($givenEmployee->getId());
        $this->assertNotNull($employee);

        // then
        self::assertEquals($givenEmployee, $employee);
    }

    /** @test */
    public function dont_find_one_by_id(): void
    {
        // given
        $givenId = '809e6b49-49de-45e2-8592-53fc0b957602';

        // when
        $employee = $this->repository()->findOne(Uuid::fromString($givenId));

        // then
        $this->assertNull($employee);
    }

    /** @test */
    public function dont_get_by_id(): void
    {
        // given
        $givenId = '809e6b49-49de-45e2-8592-53fc0b957602';

        // expect
        $this->expectException(NonExistentEntityException::class);

        // when
        $this->repository()->get(Uuid::fromString($givenId));
    }


    /** @test */
    public function add_few_and_find_all(): void
    {
        // given
        $givenFirstEmployee = EmployeeBuilder::any()->build();
        $this->save($givenFirstEmployee);

        $givenSecondEmployee = EmployeeBuilder::any()->build();
        $this->save($givenSecondEmployee);

        $givenThirdEmployee = EmployeeBuilder::any()->build();
        $this->save($givenThirdEmployee);

        // when
        $employees = $this->repository()->findAll();

        // then
        $this->assertNotEmpty($employees);
        $this->assertCount(3, $employees);
    }
}
