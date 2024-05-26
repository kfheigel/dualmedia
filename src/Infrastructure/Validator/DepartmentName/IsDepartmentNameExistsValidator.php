<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\DepartmentName;

use App\Domain\Repository\DepartmentRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsDepartmentNameExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsDepartmentNameExists) {
            throw new UnexpectedTypeException($constraint, IsDepartmentNameExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var string $value */
        if (!empty($this->departmentRepository->findOneByName($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->setCode($constraint->violationCode)
                ->addViolation();
        }
    }
}
