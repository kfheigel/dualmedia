<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\OrderId;

use App\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsOrderUuidExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsOrderUuidExists) {
            throw new UnexpectedTypeException($constraint, IsOrderUuidExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var Uuid $value */
        if (empty($this->orderRepository->findOne($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->toRfc4122())
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
