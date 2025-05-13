<?php

namespace App\Application\UseCase;

use App\Application\ViewModel\Vend\VendViewModel;
use App\Domain\Repository\TransactionRepository;

final class GetTotalInsertedAmountUseCase
{
    public function __construct( private TransactionRepository $repository) {}

    public function execute(): VendViewModel
    {
        $insertedCoinCollection = $this->repository->fetchTransaction();

        return VendViewModel::fromMessage(
            VendViewModel::MESSAGE_TOTAL_INSERTED_AMOUNT,
            $insertedCoinCollection->totalAmount()
        );
    }
}
