<?php

declare(strict_types=1);

namespace App\Tests\Integration\Domain\Service;

use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Domain\Service\CalculatorInterface;
use App\Tests\Common\IntegrationTestCase;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;

final class BonusSalaryCalculatorTest extends IntegrationTestCase
{
    private CalculatorInterface $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $calculator = $this->container->get(CalculatorInterface::class);
        Assert::assertInstanceOf(CalculatorInterface::class, $calculator);
        $this->calculator = $calculator;
    }

    /** @test */
    public function calculate_bonus_amount(): void
    {
        //given
        $givenStrategy = BonusTypeEnum::fixed->value;
        $this->calculator->setBonusTypeStrategy($givenStrategy);

        $givenBaseSalary = 1000;
        $givenSalary = $this->giveSalary($givenBaseSalary);

        $givenBonusType = BonusTypeEnum::fixed;
        $givenBonusFactor = 100;
        $givenDepartment = $this->giveDepartment($givenBonusType, $givenBonusFactor);

        $givenEmploymentDate = new DateTimeImmutable('2022-01-01');
        $givenEmployee = $this->giveEmployee($givenDepartment->getId(), $givenSalary->getId(), $givenEmploymentDate);
        $givenEmployeePayroll = $this->giveEmployeePayroll($givenEmployee);

        //when
        $employeePayroll = $this->calculator->calculate($givenEmployeePayroll);
        $expectedBonusSalary = 200;

        //then
        Assert::assertEquals($expectedBonusSalary, $employeePayroll->bonusSalary);
    }
}
