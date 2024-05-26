<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use Doctrine\Persistence\ObjectManager;
use App\Infrastructure\Repository\EmployeeRepository;
use App\Domain\Repository\EmployeeRepositoryInterface;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Tests\TestTemplate\EmployeeRepositoryTestTemplate;

final class EmployeeRepositoryTest extends EmployeeRepositoryTestTemplate
{
    use ReloadDatabaseTrait;
    
    protected function setUp():void
    {
        parent::setUp();
        self::bootKernel();
    }

    protected function repository(): EmployeeRepositoryInterface
    {
        return $this->employeeRepository;
    }

    protected function save(Employee $employee): void
    {
        $this->employeeRepository->save($employee);
        $this->em->flush();
        $this->em->clear();
    }
}
