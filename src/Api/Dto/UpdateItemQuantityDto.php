<?php

namespace App\Api\Dto;

use App\Domain\Entity\Item;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class UpdateItemQuantityDto
{
    private const MIN_QUANTITY = Item::MIN_QUANTITY;
    private const MAX_QUANTITY = Item::MAX_QUANTITY;
    private const MIN_NAME_LENGTH = Item::MIN_NAME_LENGTH;
    private const MAX_NAME_LENGTH = Item::MAX_NAME_LENGTH;

    private $name;

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
        #[OA\Property(example: 10)]
        public readonly int $quantity,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        $this->validate();
        return $this;
    }

    private function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        if (strlen($this->name) < self::MIN_NAME_LENGTH || strlen($this->name) > self::MAX_NAME_LENGTH) {
            throw new \InvalidArgumentException(
                'Name must be between ' . self::MIN_NAME_LENGTH . ' and ' . self::MAX_NAME_LENGTH . ' characters'
            );
        }
        if ($this->quantity < self::MIN_QUANTITY || $this->quantity > self::MAX_QUANTITY) {
            throw new \InvalidArgumentException(
                'Quantity must be between ' . self::MIN_QUANTITY . ' and ' . self::MAX_QUANTITY
            );
        }
    }
}
