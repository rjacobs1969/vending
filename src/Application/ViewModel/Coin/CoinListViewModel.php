<?php

namespace App\Application\ViewModel\Coin;

use App\Application\ViewModel\Coin\CoinViewModel;
use App\Domain\Collection\CoinCollection;
use App\Domain\ValueObject\Coin;

class CoinListViewModel
{
    public function __construct(private array $coinViewModels) {}

    public function toArray(): array
    {
        return array_map(
            fn (CoinViewModel $coin) => $coin->toString(),
            array_filter(
                $this->coinViewModels,
                fn ($coin) => $coin instanceof CoinViewModel
            )
        );
    }

    public function count(): int
    {
        return count($this->coinViewModels);
    }

    public function isEmpty(): bool
    {
        return empty($this->coinViewModels);
    }

    public function totalAmount(): string
    {
        $sum = (float) array_reduce(
            $this->coinViewModels,
            fn ($carry, CoinViewModel $coin) => $carry + (float) $coin->toString(),
            0
        );

        return number_format(round($sum, 2), 2, '.', '');
    }

    public static function fromCoinCollection(CoinCollection $collection): self
    {
        $coinViewModels = array_map(
            fn (Coin $coin) => CoinViewModel::fromCoin($coin),
            array_filter(
                $collection->getCoins(),
                fn ($coin) => $coin instanceof Coin
            )
        );

        return new self($coinViewModels);
    }
}
