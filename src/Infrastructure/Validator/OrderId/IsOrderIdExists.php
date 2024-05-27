<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\OrderId;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IsOrderIdExists extends Constraint
{
    public string $violationCode;

    public function __construct(int|string $code = 400, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->violationCode = (string) $code;
    }

    public string $message = 'Order with id: "{{ string }}" does not exist';
}
