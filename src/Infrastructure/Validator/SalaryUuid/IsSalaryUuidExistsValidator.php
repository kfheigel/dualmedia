<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator\SalaryUuid;

use App\Domain\Repository\SalaryRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class IsSalaryUuidExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly SalaryRepositoryInterface $salaryRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsSalaryUuidExists) {
            throw new UnexpectedTypeException($constraint, IsSalaryUuidExists::class);
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
