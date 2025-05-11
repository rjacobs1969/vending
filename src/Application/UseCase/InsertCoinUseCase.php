<?php

namespace App\Application\UseCase;

use App\Api\Dto\InsertCoinDto;
use App\Application\ViewModel\Item\ItemListViewModel;
use App\Domain\Repository\TransactionRepository;

final class InsertCoinUseCase
{
    public function __construct(
        private TransactionRepository $repository,
    ) {
    }

    public function execute(InsertCoinDto $insertCoinDto): float
    {
        $coin = $insertCoinDto->toCoin();
        $insertedCoinCollection = $this->repository->fetchTransaction();
        $insertedCoinCollection->add($coin);
        $this->repository->saveTransaction($insertedCoinCollection);

        return $insertedCoinCollection->totalAmount();
    }
}
