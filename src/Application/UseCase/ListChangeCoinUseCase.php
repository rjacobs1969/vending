<?php

namespace App\Application\UseCase;

use App\Application\ViewModel\ChangeCoin\ChangeCoinListViewModel;
use App\Domain\Repository\ChangeCoinRepository;

final class ListChangeCoinUseCase
{
    public function __construct(
        private ChangeCoinRepository $repository,
    ) {
    }

    public function execute(): ChangeCoinListViewModel
    {
        $changeCoinCollection = $this->repository->findAllChangeCoins();

        return ChangeCoinListViewModel::fromChangeCoinCollection($changeCoinCollection);
    }
}
