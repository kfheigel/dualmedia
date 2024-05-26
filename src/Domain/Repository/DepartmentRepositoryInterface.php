<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Department;
use Symfony\Component\Uid\Uuid;

interface DepartmentRepositoryInterface
{
    public function save(Department $department): void;
    public function get(Uuid $uuid): Department;
    public function findOne(Uuid $uuid): ?Department;
    public function findOneByName(string $name): ?Department;
    public function findAll(): array;
}
