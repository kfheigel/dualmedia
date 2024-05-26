<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Validator;

use App\Domain\Entity\Enum\BonusTypeEnum;
use App\Infrastructure\Validator\BonusType\IsOneOfBonusTypes;
use App\Infrastructure\Validator\BonusType\IsOneOfBonusTypesValidator;
use App\Tests\Common\ValidatorTestCase;

final class IsOneOfBonusTypesValidatorTest extends ValidatorTestCase
{
    private IsOneOfBonusTypes $givenConstraint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->givenConstraint = new IsOneOfBonusTypes();
    }

    protected function createValidator(): IsOneOfBonusTypesValidator
    {
        /** @var IsOneOfBonusTypesValidator */
        return $this->container->get(IsOneOfBonusTypesValidator::class);
    }

    /** @test */
    public function non_existing_bonus_type_raise_violation(): void
    {
        // given
        $givenFakeBonusType = "fake";

        // when
        $this->validator->validate($givenFakeBonusType, $this->givenConstraint);

        // then
        $this->buildViolation($this->givenConstraint->message)
            ->setParameter('{{ string }}', $givenFakeBonusType)
            ->setCode($this->defaultViolationCode)
            ->assertRaised();
    }

    /** @test */
    public function fixed_bonus_type_is_valid(): void
    {
        // given
        $givenFixedBonusType = BonusTypeEnum::fixed->value;

        // when
        $this->validator->validate($givenFixedBonusType, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function percentage_bonus_type_is_valid(): void
    {
        // given
        $givenPercentageBonusType = BonusTypeEnum::percentage->value;

        // when
        $this->validator->validate($givenPercentageBonusType, $this->givenConstraint);

        // then
        $this->assertNoViolation();
    }

    /** @test */
    public function validator_sets_validation_code_given_in_constructor(): void
    {
        // given
        $givenFakeBonusType = "fake";
        $givenViolationCode = 12345;

        // when
        $constraint = new IsOneOfBonusTypes(code: $givenViolationCode);
        $this->validator->validate($givenFakeBonusType, $constraint);

        // then
        $this->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $givenFakeBonusType)
            ->setCode((string) $givenViolationCode)
            ->assertRaised();
    }
}
