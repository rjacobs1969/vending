<?php

namespace App\Domain\Collection;

use App\Domain\ValueObject\Coin;

class CoinCollection implements \IteratorAggregate {
    /** @var Coin[] */
    protected array $coins;

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
        $sum = array_reduce($this->coins, fn($sum, $coin) => $sum + $coin->asFloat(), 0);

        return round($sum, 2);
    }

    public function getCoins(): array
    {
        return $this->coins;
    }

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->coins);
    }
}
