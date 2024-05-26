<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\OrderItems;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class AreOrderItemsEmptyValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AreOrderItemsEmpty) {
            throw new UnexpectedTypeException($constraint, AreOrderItemsEmpty::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var array $value */
        if (empty($value)) {
            $this->context->buildViolation($constraint->message)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
