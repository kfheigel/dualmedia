<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\DepartmentUuid;

use App\Domain\Repository\DepartmentRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsDepartmentUuidExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $salaryRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsDepartmentUuidExists) {
            throw new UnexpectedTypeException($constraint, IsDepartmentUuidExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var Uuid $value */
        if (empty($this->salaryRepository->findOne($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value->toRfc4122())
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
