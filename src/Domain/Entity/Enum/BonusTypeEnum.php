<?php

declare(strict_types=1);

namespace App\Domain\Entity\Enum;

enum BonusTypeEnum: string
{
    case percentage = 'percentage';
    case fixed = 'fixed';

    public static function getAllValues(): array
    {
        return array_values(array_column(BonusTypeEnum::cases(), 'value'));
    }
}
