<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Infrastructure\Repository\OrderRepository;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $orderDate;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $customerName;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $customerEmail;
    
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $orderItems;

    public function __construct(
        string $customerName,
        string $customerEmail,
        array $orderItems = [],
        ?Uuid $id = null
    ) {
        $this->orderDate = new DateTimeImmutable();
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->orderItems = new ArrayCollection($orderItems);
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOrderDate(): DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }
}
