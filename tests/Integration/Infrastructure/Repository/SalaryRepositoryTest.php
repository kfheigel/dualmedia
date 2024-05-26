<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Repository;

use App\Domain\Entity\Salary;
use App\Domain\Repository\SalaryRepositoryInterface;
use App\Infrastructure\Repository\SalaryRepository;
use App\Tests\TestTemplate\SalaryRepositoryTestTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use PHPUnit\Framework\Assert;

final class SalaryRepositoryTest extends SalaryRepositoryTestTemplate
{
    use ReloadDatabaseTrait;

    protected function setUp():void
    {
        parent::setUp();
        self::bootKernel();
    }

    protected function repository(): SalaryRepositoryInterface
    {
        return $this->salaryRepository;
    }

    protected function save(Salary $salary): void
    {
        $this->salaryRepository->save($salary);
        $this->em->flush();
        $this->em->clear();
    }
}
