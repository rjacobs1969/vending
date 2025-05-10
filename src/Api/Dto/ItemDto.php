<?php

namespace App\Api\Dto;

use App\Domain\Entity\Item;

class ItemDto
{
    public function __construct(
        public readonly string $name,
        public readonly int $quantity,
        public readonly float $price,
    ) {
    }

    public function toItem(): Item
    {
        return new Item(
            $this->name,
            $this->price,
            $this->quantity,
        );
    }
}
