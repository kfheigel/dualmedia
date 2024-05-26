<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\BonusType;

use App\Domain\Entity\Enum\BonusTypeEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsOneOfBonusTypesValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsOneOfBonusTypes) {
            throw new UnexpectedTypeException($constraint, IsOneOfBonusTypes::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!in_array($value, array_column(BonusTypeEnum::cases(), 'value'))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
