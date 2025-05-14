<?php

namespace App\Api\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;


class UpdateChangeCoinQuantityDto
{
    private const VALID_COIN_VALUES = [0.05, 0.10, 0.25];
    private const MIN_QUANTITY = 0;
    private const MAX_QUANTITY = 999;

    public function __construct(
        #[Assert\Type(
            type: 'float',
            message: 'Coin must be a float value'
        )]
        #[Assert\Choice(
            choices: self::VALID_COIN_VALUES,
            message: 'Invalid coin value. Allowed values are: {{ choices }}'
        )]
        #[Groups(["update"])]
        public readonly float $coin,
        #[Assert\Type(
            type: 'integer',
            message: 'Quantity must be an integer'
        )]
        #[Assert\Range(
            min: self::MIN_QUANTITY,
            max: self::MAX_QUANTITY,
            notInRangeMessage: 'Quantity must be between {{ min }} and {{ max }}'
        )]
        #[Groups(["update"])]
        #[OA\Property(example: 10)]

        public readonly int $quantity,
    ) {
    }

    public function getCoin(): float
    {
        return $this->coin;
    }

    public function getCoinValue(): int
    {
        return (int)($this->getCoin() * 100);
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function validate(): void
    {
        if ($this->quantity < self::MIN_QUANTITY || $this->quantity > self::MAX_QUANTITY) {
            throw new \InvalidArgumentException(
                'Quantity must be between ' . self::MIN_QUANTITY . ' and ' . self::MAX_QUANTITY
            );
        }

        if (!in_array($this->coin, self::VALID_COIN_VALUES)) {
            throw new \InvalidArgumentException(
                'Invalid coin value. Allowed values are: ' . implode(', ', self::VALID_COIN_VALUES)
            );
        }
    }
}
