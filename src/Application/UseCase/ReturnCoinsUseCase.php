<?php

namespace App\Application\UseCase;

use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Vend\VendViewModel;
use App\Domain\Collection\ReturnCoinCollection;
use App\Domain\Repository\TransactionRepository;

final class ReturnCoinsUseCase
{
    public function __construct(private TransactionRepository $repository)
    {
    }

    public function execute(): VendViewModel
    {
        $insertedCoinCollection = $this->repository->fetchTransaction();
        $returnCoinCollection = new ReturnCoinCollection($insertedCoinCollection->returnAllCoins());
        $this->repository->saveTransaction($insertedCoinCollection);

        return VendViewModel::fromCoinItemMessage(
            CoinListViewModel::fromCoinCollection($returnCoinCollection),
            null,
            $returnCoinCollection->empty() ? VendViewModel::MESSAGE_NO_RETURN_COINS : VendViewModel::MESSAGE_RETURN_COINS
        );
    }
}
