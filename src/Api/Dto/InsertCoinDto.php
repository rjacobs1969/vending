<?php

namespace App\Api\Dto;

use App\Domain\ValueObject\Coin;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

class InsertCoinDto
{
    private const ALLOWED_COIN_VALUES = [0.05, 0.10, 0.25, 1.0];

    public function __construct(

        #[Assert\Type(
            type: 'float',
            message: 'Coin must be an float value'
        )]
        #[Assert\Choice(
            choices: self::ALLOWED_COIN_VALUES,
            message: 'Invalid coin value. Allowed values are: {{ choices }}'
        )]
        #[Groups(["create"])]
        #[OA\Property(example: 0.25)]
        public readonly float $coin,
    ) {
    }

    public function toCoin(): Coin
    {
        return Coin::fromCurrencyValue($this->coin);
    }

    public function coinValue(): float
    {
        return $this->coin;
    }

    public function validateCoinValue(): void
    {
        if (!in_array($this->coin, self::ALLOWED_COIN_VALUES)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid coin value: %s. Allowed values are: %s', $this->coin, implode(', ', self::ALLOWED_COIN_VALUES))
            );
        }
    }
}
