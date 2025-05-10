<?php

namespace App\Application\ViewModel\Item;

use App\Domain\Entity\Item;

class ItemViewModel
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $price,
        public readonly int $quantity
    ) {}

    /**
     * @return array{id: int, name: string, price: string, quantity_available: int}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->formatPrice($this->price),
            'quantity_available' => $this->quantity,
        ];
    }

    public static function fromItem(Item $item): self
    {
        return new self(
            $item->getId(),
            $item->getName(),
            $item->getPrice(),
            $item->getQuantity()
        );
    }

    private function formatPrice(float $price): string
    {
        return number_format($price, 2, '.', '');
    }
}
