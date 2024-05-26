<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Domain\Entity\Department;
use App\Domain\Entity\Enum\BonusTypeEnum;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

final class DepartmentBuilder
{
    private Uuid $id;
    private string $name;
    private BonusTypeEnum $bonusType;
    private int $bonusFactor;

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

    public function withBonusType(BonusTypeEnum $bonusType): self
    {
        $this->bonusType = $bonusType;

        return $this;
    }

    public function withBonusFactor(int $bonusFactor): self
    {
        $this->bonusFactor = $bonusFactor;

        return $this;
    }

    public static function any(): self
    {
        return new DepartmentBuilder();
    }

    public function build(): Department
    {
        $faker = Factory::create();

        /** @var BonusTypeEnum $randomBonusType */
        $randomBonusType = $faker->randomElement(BonusTypeEnum::cases());

        return new Department(
            $this->name ?? $faker->name(),
            $this->bonusType ?? $randomBonusType,
            $this->bonusFactor ?? $faker->numberBetween(0, 100),
            $this->id ?? Uuid::v4()
        );
    }
}
