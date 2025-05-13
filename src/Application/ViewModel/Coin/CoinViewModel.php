<?php

namespace App\Application\ViewModel\Coin;

use App\Domain\ValueObject\Coin;

class CoinViewModel
{
    public function __construct(public readonly string $coinValue) {}

    public function toString(): string
    {
        return $this->coinValue;
    }

    public static function fromCoin(Coin $coin): self
    {
        return new self($coin->asCurrency());
    }
}
