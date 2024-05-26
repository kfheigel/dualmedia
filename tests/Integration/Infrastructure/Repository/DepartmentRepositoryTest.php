<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\Department;
use App\Domain\Repository\DepartmentRepositoryInterface;
use App\Infrastructure\Repository\DepartmentRepository;
use App\Tests\TestTemplate\DepartmentRepositoryTestTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;

final class DepartmentRepositoryTest extends DepartmentRepositoryTestTemplate
{
    use ReloadDatabaseTrait;

    protected function setUp():void
    {
        parent::setUp();
        self::bootKernel();
    }

    protected function repository(): DepartmentRepositoryInterface
    {
        return $this->departmentRepository;
    }

    protected function save(Department $department): void
    {
        $this->departmentRepository->save($department);
        $this->em->flush();
        $this->em->clear();
    }
}