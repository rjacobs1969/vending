<?php

namespace App\Domain\Collection;

use App\Domain\ValueObject\Coin;

final class InsertedCoinCollection implements \IteratorAggregate {
    /** @var Coin[] */
    private array $coins;

    public function __construct(array $coins = []) {
        foreach ($coins as $coin) {
            if (!$coin instanceof Coin) {
                throw new \InvalidArgumentException("Only Coin objects are allowed.");
            }
        }
        $this->coins = $coins;
    }

    public function add(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function empty(): void
    {
        $this->coins = [];
    }

    public function totalAmount(): float {
        return array_reduce($this->coins, fn($sum, $coin) => $sum + $coin->asFloat(), 0);
    }

    public function getCoins(): array
    {
        return array_map(
            fn(Coin $coin) => $coin->asCurrency(),
            $this->coins
        );
    }

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->coins);
    }

    public function __toString(): string
    {
        return implode(", ", array_map(fn(Coin $coin) => $coin->asCurrency(), $this->coins));
    }

    public function toJson(): string
    {
        return json_encode(array_map(fn(Coin $coin) => $coin->value, $this->coins));
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON data");
        }
        $coins = array_map(fn($value) => Coin::from($value), $data);

        return new self($coins);
    }
}
