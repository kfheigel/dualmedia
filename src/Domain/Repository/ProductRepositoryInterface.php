<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Product;
use Symfony\Component\Uid\Uuid;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;
    public function get(Uuid $uuid): Product;
    public function findOne(Uuid $uuid): ?Product;
    public function findAll(): array;
}
