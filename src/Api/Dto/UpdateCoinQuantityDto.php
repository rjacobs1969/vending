<?php

namespace App\Api\Dto;

use App\Domain\ValueObject\Coin;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class UpdateCoinQuantityDto
{
    private const MIN_QUANTITY = 0;
    private const MAX_QUANTITY = 100;
    private const ALLOWED_COIN_VALUES = [0.05, 0.10, 0.25];

    public function __construct(

        #[Assert\Type(
            type: 'float',
            message: 'Coin must be an float value'
        )]
        #[Assert\Choice(
            choices: self::ALLOWED_COIN_VALUES,
            message: 'Invalid coin value. Allowed values are: {{ choices }}'
        )]
        #[Groups(["updateQuantity"])]
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
        #[Groups(["updateQuantity"])]
        public readonly int $quantity,
    ) {
    }

    public function toCoin(): Coin
    {
        return Coin::fromCurrencyValue($this->coin);
    }
}
