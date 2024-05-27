<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Repository\NonExistentEntityException;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function get(int $id): Product
    {
        $order = $this->findOne($id);

        if (!$order) {
            throw new NonExistentEntityException(Product::class, (string)$id);
        }

        return $order;
    }

    public function findOne(int $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
