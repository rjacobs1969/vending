<?php

namespace App\Domain\Collection;

use App\Domain\ValueObject\Coin;

final class InsertedCoinCollection extends CoinCollection implements \IteratorAggregate
{
    public function toJson(): string
    {
        return json_encode(array_map(fn(Coin $coin) => $coin->value, $this->coins));
    }

    public static function fromJson(string $json): self
    {
        $coins = [];
        $data = json_decode($json, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $coins = array_map(fn($value) => Coin::from($value), $data);
        }

        return new self($coins);
    }
}
