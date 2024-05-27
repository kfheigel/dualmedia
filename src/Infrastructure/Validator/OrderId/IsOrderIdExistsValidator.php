<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\OrderId;

use App\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsOrderIdExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsOrderIdExists) {
            throw new UnexpectedTypeException($constraint, IsOrderIdExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var int $value */
        if (empty($this->orderRepository->findOne($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', (string)$value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
