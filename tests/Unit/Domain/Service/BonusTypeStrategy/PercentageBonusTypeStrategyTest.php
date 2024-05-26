<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\BonusTypeStrategy;

use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Domain\Service\BonusTypeStrategy\PercentageBonusTypeStrategy;
use App\Tests\Common\UnitTestCase;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;

final class PercentageBonusTypeStrategyTest extends UnitTestCase
{
    private PercentageBonusTypeStrategy $bonusTypeStrategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bonusTypeStrategy = new PercentageBonusTypeStrategy();
    }

    /**
     * @test
     * @dataProvider provideValues
     */
    public function calculate_bonus_amount_based_on_department_with_percentage_bonus_type(int $givenBaseSalary, int $givenBonusFactor, int $expectedBonusSalary): void
    {
        //given
        $givenSalary = $this->giveSalary($givenBaseSalary);

        $givenBonusType = BonusTypeEnum::percentage;
        $givenDepartment = $this->giveDepartment($givenBonusType, $givenBonusFactor);

        $givenEmploymentDate = new DateTimeImmutable('2020-01-01');
        $givenEmployee = $this->giveEmployee($givenDepartment->getId(), $givenSalary->getId(), $givenEmploymentDate);
        $givenEmployeePayroll = $this->giveEmployeePayroll($givenEmployee, $givenDepartment, $givenSalary);

        //when
        $employeePayroll = $this->bonusTypeStrategy->calculateBonusAmount($givenEmployeePayroll);

        //then
        Assert::assertEquals($expectedBonusSalary, $employeePayroll->bonusSalary);
    }

    public function provideValues(): array
    {
        return [
            [1000, 10, 100],
            [1000, 100, 1000],
            [2000, 10, 200],
            [2000, 20, 400],
            [3000, 5, 150],
        ];
    }
}