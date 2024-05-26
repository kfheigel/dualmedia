<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Domain\Entity\Salary;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

final class SalaryBuilder
{
    private Uuid $id;
    private int $baseSalary;
    private ?int $bonusSalary;

    public function withId(Uuid $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withBaseSalary(int $baseSalary): self
    {
        $this->baseSalary = $baseSalary;

        return $this;
    }

    public function withBonusSalary(?int $bonusSalary): self
    {
        $this->bonusSalary = $bonusSalary;

        return $this;
    }

    public static function any(): self
    {
        return new SalaryBuilder();
    }

    public function build(): Salary
    {
        $faker = Factory::create();

        return new Salary(
            $this->baseSalary ?? $faker->numberBetween(1000, 2000),
                $this->bonusSalary ?? $faker->numberBetween(0, 100),
                $this->id ?? Uuid::v4()
        );
    }
}
