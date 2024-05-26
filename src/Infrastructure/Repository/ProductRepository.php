<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Repository\NonExistentEntityException;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    public function get(Uuid $uuid): Product
    {
        $product = $this->findOne($uuid);

        if (!$product) {
            throw new NonExistentEntityException(Product::class, $uuid->toRfc4122());
        }

        return $product;
    }

    public function findOne(Uuid $uuid): ?Product
    {
        return $this->createQueryBuilder('p')
        ->where('p.id = :uuid')
        ->setParameter('uuid', $uuid->toRfc4122())
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
