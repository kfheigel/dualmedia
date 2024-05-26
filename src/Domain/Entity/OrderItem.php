<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use App\Infrastructure\Repository\OrderItemRepository;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private $order;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private $unitPrice;

    public function __construct(
        Orders $order, 
        Product $product, 
        int $quantity, 
        float $unitPrice,
        ?Uuid $id = null
    ) {
        $this->order = $order;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrder(): ?Orders
    {
        return $this->order;
    }

    public function setOrder(?Orders $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}
