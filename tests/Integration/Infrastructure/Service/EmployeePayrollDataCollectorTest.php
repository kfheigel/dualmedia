<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Service;

use App\Domain\Entity\Employee;
use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Infrastructure\Service\EmployeePayrollDataCollector;
use App\Tests\Asserts\EmployeePayrollAssert;
use App\Tests\Common\IntegrationTestCase;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;

final class EmployeePayrollDataCollectorTest extends IntegrationTestCase
{
    private EmployeePayrollDataCollector $dataCollector;

    protected function setUp(): void
    {
        parent::setUp();
        $dataCollector = $this->container->get(EmployeePayrollDataCollector::class);
        Assert::assertInstanceOf(EmployeePayrollDataCollector::class, $dataCollector);
        $this->dataCollector = $dataCollector;
    }

    /** @test */
    public function collect_employee_payroll_data(): void
    {
        //given
        $givenBaseSalary = 2000;
        $givenSalary = $this->giveSalary($givenBaseSalary);

        $givenBonusType = BonusTypeEnum::percentage;
        $givenBonusFactor = 50;
        $givenDepartment = $this->giveDepartment($givenBonusType, $givenBonusFactor);

        $givenEmploymentDate = new DateTimeImmutable('2020-01-01');
        $givenEmployee = $this->giveEmployee($givenDepartment->getId(), $givenSalary->getId(), $givenEmploymentDate);

        //when
        $employeePayroll = $this->dataCollector->collect($givenEmployee);

        //then
        EmployeePayrollAssert::hasTheSameProperties(
            $givenEmployee,
            $givenDepartment,
            $givenSalary,
            $employeePayroll
        );
    }
}
