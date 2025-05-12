<?php

namespace App\Application\ViewModel\ChangeCoin;

class ChangeCoinViewModel
{
    public function __construct(
        public readonly float $coinValue,
        public readonly int $amountAvailable = 0,
    ) {}

    public function toArray(): array
    {
        return [
            'coin' => round($this->coinValue/100, 2),
            'number_of_coins' => $this->amountAvailable,
            'value' => $this->totalAmount(),
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
