<?php

namespace App\Domain\ValueObject;

enum Coin: int
{
    case HUNDRED = 100;
    case TWENTY_FIVE = 25;
    case TEN = 10;
    case FIVE = 5;

    public function asFloat(): float
    {
        return round($this->value / 100, 2);
    }

    public function asCurrency(): string
    {
        return number_format($this->asFloat(), 2, '.', '');
    }

    public static function fromCurrencyValue(float $value): self
    {
        return match ($value) {
            1.00 => self::HUNDRED,
            0.25 => self::TWENTY_FIVE,
            0.10 => self::TEN,
            0.05 => self::FIVE,
            default => throw new \InvalidArgumentException("Invalid coin value: $value"),
        };
    }
}
