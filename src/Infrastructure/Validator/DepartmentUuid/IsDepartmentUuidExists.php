<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\DepartmentUuid;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsDepartmentUuidExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->violationCode = (string) $code;
    }

    public string $message = 'Department with Uuid: "{{ string }}" does not exist';
}
