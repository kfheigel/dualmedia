<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Salary;
use Symfony\Component\Uid\Uuid;

interface SalaryRepositoryInterface
{
    public function save(Salary $salary): void;
    public function get(Uuid $uuid): Salary;
    public function findOne(Uuid $uuid): ?Salary;
    public function findAll(): array;
}
