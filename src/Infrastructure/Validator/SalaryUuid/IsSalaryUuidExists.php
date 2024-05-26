<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\SalaryUuid;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsSalaryUuidExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->violationCode = (string) $code;
    }

    public string $message = 'Salary with Uuid: "{{ string }}" does not exist';
}
