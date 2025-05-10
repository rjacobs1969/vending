<?php

namespace App\Domain\Entity;

use App\Infrastructure\Persistence\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: "items")]
class Item {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 64, unique: true)]
    private string $name;

    #[ORM\Column(type: "float")]
    private float $price;

    #[ORM\Column(type: "integer")]
    private int $quantity;

    public function __construct(string $name, float $price, int $quantity)
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setQuantity($quantity);
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
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
        $this->quantity = $quantity;

        return $this;
    }
}
