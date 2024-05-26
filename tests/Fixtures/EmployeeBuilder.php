<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use DateTimeImmutable;
use DateTimeInterface;
use Faker\Factory;
use App\Domain\Entity\Employee;
use Symfony\Component\Uid\Uuid;

final class EmployeeBuilder
{
    private Uuid $id;
    private string $name;
    private string $surname;
    private Uuid $departmentId;
    private Uuid $salaryId;
    private DateTimeInterface $employmentDate;

    public function withId(Uuid $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function withSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
    public function withDepartmentId(uuid $departmentId): self
    {
        $this->departmentId = $departmentId;

        return $this;
    }
    public function withSalaryId(uuid $salaryId): self
    {
        $this->salaryId = $salaryId;

        return $this;
    }
    public function withEmploymentDate(DateTimeInterface $employmentDate): self
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }

    public static function any(): self
    {
        return new EmployeeBuilder();
    }

    public function build(): Employee
    {
        $faker = Factory::create();

        return new Employee(
            $this->name ?? $faker->firstName(),
            $this->surname ?? $faker->lastName(),
            $this->departmentId ?? Uuid::v4(),
            $this->salaryId ?? Uuid::v4(),
            $this->employmentDate ?? new DateTimeImmutable($faker->date('Y-m-d')),
            $this->id ?? Uuid::v4()
        );
    }
}
