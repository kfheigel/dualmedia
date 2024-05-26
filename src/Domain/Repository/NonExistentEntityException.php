<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use LogicException;

final class NonExistentEntityException extends LogicException
{
    public function __construct(string $entityName, string $entityId)
    {
        parent::__construct(
            sprintf("In this way, you should not request a %s that does not exist - with id: %s", $entityName, $entityId)
        );
    }
}
