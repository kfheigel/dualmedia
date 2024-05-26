<?php

declare(strict_types=1);

namespace App\ReadModel\EmployeesPayroll;

use App\Domain\Messenger\MessageBus\QueryInterface;

final class EmployeesPayrollQuery implements QueryInterface
{
    public function __construct()
    {
    }
}
