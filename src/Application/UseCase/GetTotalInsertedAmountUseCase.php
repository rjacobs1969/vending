<?php

namespace App\Application\UseCase;

use App\Domain\Repository\TransactionRepository;

final class GetTotalInsertedAmountUseCase
{
    public function __construct(
        private TransactionRepository $repository,
    ) {
    }

    public function execute(): float
    {
        $insertedCoinCollection = $this->repository->fetchTransaction();

        return $insertedCoinCollection->totalAmount();
    }
}
