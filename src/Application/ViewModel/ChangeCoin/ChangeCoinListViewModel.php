<?php

namespace App\Application\ViewModel\ChangeCoin;

use App\Domain\Collection\ChangeCoinCollection;

class ChangeCoinListViewModel
{
    public function __construct(private array $changeCoinViewModels)
    {
    }

    public function toArray(): array
    {
        return [
            'coins' => array_map(
                fn (ChangeCoinViewModel $coin) => $coin->toArray(),
                array_filter(
                    $this->changeCoinViewModels,
                    fn ($coin) => $coin instanceof ChangeCoinViewModel
                )
            ),
            'total_value' => $this->totalAmount(),
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
