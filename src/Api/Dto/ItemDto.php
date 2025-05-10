<?php

namespace App\Api\Dto;

use App\Domain\Entity\Item;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * Data Transfer Object for Item entity
 * Handles validation and serialization of item data for API operations
 */
class ItemDto
{
    // Name constraints
    private const MIN_NAME_LENGTH = Item::MIN_NAME_LENGTH;
    private const MAX_NAME_LENGTH = Item::MAX_NAME_LENGTH;

    // Price constraints
    private const MIN_PRICE = Item::MIN_PRICE;
    private const MAX_PRICE = Item::MAX_PRICE;

    // Quantity constraints
    private const MIN_QUANTITY = Item::MIN_QUANTITY;
    private const MAX_QUANTITY = Item::MAX_QUANTITY;

    public function __construct(
        #[Assert\NotBlank(
            message: 'Name cannot be empty'
        )]
        #[Assert\Type(
            type: 'string',
            message: 'Name must be a string'
        )]
        #[Assert\Length(
            min: self::MIN_NAME_LENGTH,
            max: self::MAX_NAME_LENGTH,
            minMessage: 'Name must be at least {{ limit }} characters long',
            maxMessage: 'Name cannot be longer than {{ limit }} characters'
        )]
        #[Groups(['create'])]
        public readonly string $name,

        #[Assert\Type(
            type: 'integer',
            message: 'Quantity must be an integer'
        )]
        #[Assert\Range(
            min: self::MIN_QUANTITY,
            max: self::MAX_QUANTITY,
            notInRangeMessage: 'Quantity must be between {{ min }} and {{ max }}'
        )]
        #[Groups(['create', 'update'])]
        public readonly int $quantity,

        #[Assert\Type(
            type: 'float',
            message: 'Price must be a number'
        )]
        #[Assert\Range(
            min: self::MIN_PRICE,
            max: self::MAX_PRICE,
            notInRangeMessage: 'Price must be between {{ min }} and {{ max }}'
        )]
        #[Groups(['create', 'update'])]
        public readonly float $price,
    ) {
    }

    /**
     * Converts the DTO to an Item entity
     */
    public function toItem(): Item
    {
        return new Item(
            $this->name,
            $this->price,
            $this->quantity,
        );
    }
}
