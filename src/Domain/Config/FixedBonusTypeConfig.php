<?php

declare(strict_types=1);

namespace App\Domain\Config;

final readonly class FixedBonusTypeConfig
{
    public int $yearsOfWorkAccountableToBonus;

    public function __construct()
    {
        $this->yearsOfWorkAccountableToBonus = 10;
    }
}
