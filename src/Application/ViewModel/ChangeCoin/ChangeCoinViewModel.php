<?php

namespace App\Application\ViewModel\ChangeCoin;

class ChangeCoinViewModel
{
    public const FIELD_COIN = 'coin';
    public const FIELD_QUANTITY = 'number_of_coins';
    public const FIELD_VALUE = 'value';

    public function __construct(public readonly float $coinValue, public readonly int $amountAvailable = 0) {}

    public function toArray(): array
    {
        return [
            static::FIELD_COIN => round($this->coinValue/100, 2),
            static::FIELD_QUANTITY => $this->amountAvailable,
            static::FIELD_VALUE => $this->totalAmount(),
        ];
    }

    public function value(): float
    {
        return $this->coinValue;
    }

    public function amountAvailable(): int
    {
        return $this->amountAvailable;
    }

    public function totalAmount(): float
    {
        return round(($this->value() * $this->amountAvailable()) / 100, 2);
    }

    public static function fromCoinValue(float $coinValue, int $amountAvailable): self
    {
        return new self(
            coinValue: $coinValue,
            amountAvailable: $amountAvailable,
        );
    }
}
