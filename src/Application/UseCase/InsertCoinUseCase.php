<?php

namespace App\Application\UseCase;

use App\Api\Dto\InsertCoinDto;
use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Coin\CoinViewModel;
use App\Application\ViewModel\Vend\VendViewModel;
use App\Domain\Repository\TransactionRepository;

final class InsertCoinUseCase
{
    public function __construct(private TransactionRepository $repository) {}

    public function execute(InsertCoinDto $insertCoinDto): VendViewModel
    {
        $insertedCoinCollection = $this->repository->fetchTransaction();

        try {
            $insertCoinDto->validateCoinValue();
            $coin = $insertCoinDto->toCoin();
            $insertedCoinCollection->add($coin);
            $this->repository->saveTransaction($insertedCoinCollection);
            return VendViewModel::fromMessage(
                VendViewModel::MESSAGE_COIN_ACCEPTED,
                $insertedCoinCollection->totalAmount()
            );
        } catch (\InvalidArgumentException) {
            // return the invalid coin to the user
            return VendViewModel::fromCoinItemMessage(
                new CoinListViewModel([new CoinViewModel((string) $insertCoinDto->coinValue())]),
                null,
                VendViewModel::MESSAGE_COIN_NOT_ACCEPTED,
                $insertedCoinCollection->totalAmount()
            );
        }
    }
}
