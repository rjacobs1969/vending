<?php

namespace App\Application\ViewModel\ChangeCoin;

use App\Domain\Collection\ChangeCoinCollection;

class ChangeCoinListViewModel
{
    public const FIELD_COINS = 'coins';
    public const FIELD_TOTAL_VALUE = 'total_value';

    public function __construct(private array $changeCoinViewModels) {}

    public function toArray(): array
    {
        return [
            self::FIELD_COINS => array_map(
                fn (ChangeCoinViewModel $coin) => $coin->toArray(),
                array_filter(
                    $this->changeCoinViewModels,
                    fn ($coin) => $coin instanceof ChangeCoinViewModel
                )
            ),
            self::FIELD_TOTAL_VALUE => $this->totalAmount(),
        ];
    }

    public function totalAmount(): float
    {
        $sum = (float) array_reduce(
            $this->changeCoinViewModels,
            fn ($carry, ChangeCoinViewModel $coin) => $carry + (float) $coin->totalAmount(),
            0
        );

        return round($sum, 2);
    }

    public static function fromChangeCoinCollection(ChangeCoinCollection $collection): self
    {
        $coinViewModels = [];
        foreach ($collection->getChangeCoinsAvailable() as $coinValue => $amountAvailable) {
            $viewModel = new ChangeCoinViewModel(
                coinValue: $coinValue,
                amountAvailable: $amountAvailable
            );
            $coinViewModels[] = $viewModel;
        }

        return new self($coinViewModels);
    }
}
