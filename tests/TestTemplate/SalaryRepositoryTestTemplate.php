<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Salary;
use App\Domain\Repository\NonExistentEntityException;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\SalaryBuilder;
use Symfony\Component\Uid\Uuid;

abstract class SalaryRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): SalaryRepositoryInterface;
    abstract protected function save(Salary $salary): void;

    /** @test */
    public function add_and_find_one_by_id(): void
    {
        // given
        $givenId = '809e6b49-49de-45e2-8592-53fc0b957602';
        $givenSalary = SalaryBuilder::any()
            ->withId(Uuid::fromString($givenId))
            ->build();

        // when
        $this->save($givenSalary);
        $employee = $this->repository()->findOne($givenSalary->getId());
        $this->assertNotNull($employee);

        // then
        self::assertEquals($givenSalary, $employee);
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
        $givenFirstSalary = SalaryBuilder::any()->build();
        $this->save($givenFirstSalary);

        $givenSecondSalary = SalaryBuilder::any()->build();
        $this->save($givenSecondSalary);

        $givenThirdSalary = SalaryBuilder::any()->build();
        $this->save($givenThirdSalary);

        // when
        $salaries = $this->repository()->findAll();

        // then
        $this->assertNotEmpty($salaries);
        $this->assertCount(3, $salaries);
    }
}