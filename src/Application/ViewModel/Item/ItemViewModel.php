<?php

namespace App\Application\ViewModel\Item;

use App\Domain\Entity\Item;

class ItemViewModel
{
    public const FIELD_NAME = 'name';
    public const FIELD_PRICE = 'price';
    public const FIELD_QUANTITY = 'quantity_available';

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $price,
        public readonly int $quantity
    ) {}

    /**
     * @return array{name: string, price: string, quantity_available: int}
     */
    public function toArray(): array
    {
        return [
            self::FIELD_NAME => $this->name,
            self::FIELD_PRICE => $this->formatPrice($this->price),
            self::FIELD_QUANTITY => $this->quantity,
        ];
    }

    public static function fromItem(Item $item): self
    {
        return new self(
            $item->getId(),
            $item->getName(),
            $item->getPrice()/100,
            $item->getQuantity()
        );
    }

    public function toString(): string
    {
        return $this->name;
    }

    private function formatPrice(float $price): string
    {
        return number_format($price, 2, '.', '');
    }
}
