<?php

namespace App\Domain\Collection;

use App\Domain\ValueObject\Coin;

final class ReturnCoinCollection extends CoinCollection implements \IteratorAggregate
{
    public function toString(): string
    {
        return implode(", ", array_map(fn(Coin $coin) => $coin->asCurrency(), $this->coins));
    }
}
