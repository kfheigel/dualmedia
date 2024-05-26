<?php

declare(strict_types=1);

namespace App\Tests\TestTemplate;

use App\Domain\Entity\Department;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\DepartmentBuilder;
use Symfony\Component\Uid\Uuid;

abstract class DepartmentRepositoryTestTemplate extends UnitTestCase
{
    abstract protected function repository(): DepartmentRepositoryInterface;
    abstract protected function save(Department $department): void;

    /** @test */
    public function add_and_find_one_by_id(): void
    {
        // given
        $givenId = '809e6b49-49de-45e2-8592-53fc0b957602';
        $givenDepartment = DepartmentBuilder::any()
            ->withId(Uuid::fromString($givenId))
            ->build();

        // when
        $this->save($givenDepartment);
        $department = $this->repository()->findOne($givenDepartment->getId());
        $this->assertNotNull($department);

        // then
        self::assertEquals($givenDepartment, $department);
    }

    /** @test */
    public function dont_find_one_by_id(): void
    {
        // given
        $givenId = '809e6b49-49de-45e2-8592-53fc0b957602';

        // when
        $department = $this->repository()->findOne(Uuid::fromString($givenId));

        // then
        $this->assertNull($department);
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
    public function find_one_by_name(): void
    {
        // given
        $givenName = 'test department';
        $givenDepartment = DepartmentBuilder::any()
            ->withName($givenName)
            ->build();

        // when
        $this->save($givenDepartment);
        $department = $this->repository()->findOneByName($givenName);
        $this->assertNotNull($department);

        // then
        self::assertEquals($givenDepartment, $department);
    }


    /** @test */
    public function add_few_and_find_all(): void
    {
        // given
        $givenFirstDepartment = DepartmentBuilder::any()->build();
        $this->save($givenFirstDepartment);

        $givenSecondDepartment = DepartmentBuilder::any()->build();
        $this->save($givenSecondDepartment);

        $givenThirdDepartment = DepartmentBuilder::any()->build();
        $this->save($givenThirdDepartment);

        // when
        $departments = $this->repository()->findAll();

        // then
        $this->assertNotEmpty($departments);
        $this->assertCount(3, $departments);
    }
}