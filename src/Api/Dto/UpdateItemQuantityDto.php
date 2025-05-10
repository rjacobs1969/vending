<?php

namespace App\Api\Dto;

use App\Domain\Entity\Item;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class UpdateItemQuantityDto
{
    private const MIN_QUANTITY = Item::MIN_QUANTITY;
    private const MAX_QUANTITY = Item::MAX_QUANTITY;

    private $id;

    public function __construct(
        #[Assert\Type(
            type: 'integer',
            message: 'Quantity must be an integer'
        )]
        #[Assert\Range(
            min: self::MIN_QUANTITY,
            max: self::MAX_QUANTITY,
            notInRangeMessage: 'Quantity must be between {{ min }} and {{ max }}'
        )]
        #[Groups(["updateQuantity"])]
        public readonly int $quantity,
    ) {
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
