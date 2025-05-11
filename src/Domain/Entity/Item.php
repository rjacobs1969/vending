<?php

namespace App\Domain\Entity;

use App\Infrastructure\Persistence\Repository\DoctrineItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DoctrineItemRepository::class)]
#[ORM\Table(name: "items")]
class Item {
    // Domain constraints
    public const MIN_NAME_LENGTH = 3;
    public const MAX_NAME_LENGTH = 128;
    public const MIN_PRICE = 5;
    public const MAX_PRICE = 500;
    public const MIN_QUANTITY = 0;
    public const MAX_QUANTITY = 99;

    #[Groups(['item:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[Groups(['item:read'])]
    #[ORM\Column(type: "string", length: self::MAX_NAME_LENGTH, unique: true)]
    private string $name;

    #[Groups(['item:read'])]
    #[ORM\Column(type: "integer")]
    private float $price;

    #[Groups(['item:read'])]
    #[ORM\Column(type: "integer")]
    private int $quantity;

    public function __construct(string $name, int $price, int $quantity = self::MIN_QUANTITY)
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setQuantity($quantity);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        if ($quantity < self::MIN_QUANTITY || $quantity > self::MAX_QUANTITY) {
            throw new \DomainException('Invalid quantity');
        }
        $this->quantity = $quantity;

        return $this;
    }
}
