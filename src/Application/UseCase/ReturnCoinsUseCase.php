<?php

namespace App\Application\UseCase;

use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Coin\CoinViewModel;
use App\Domain\Collection\ReturnCoinCollection;
use App\Domain\Repository\TransactionRepository;

final class ReturnCoinsUseCase
{
    public function __construct(
        private TransactionRepository $repository,
    ) {
    }

    public function execute(): CoinListViewModel
    {
        $insertedCoinCollection = $this->repository->fetchTransaction();
        $returnCoinCollection = new ReturnCoinCollection(
            $insertedCoinCollection->getCoins()
        );
        $insertedCoinCollection->empty();
        $this->repository->saveTransaction($insertedCoinCollection);

        return CoinListViewModel::fromCoinCollection($returnCoinCollection);
    }
}
