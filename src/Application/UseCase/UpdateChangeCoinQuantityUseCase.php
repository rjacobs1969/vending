<?php

namespace App\Application\UseCase;

use App\Api\Dto\UpdateChangeCoinQuantityDto;
use App\Application\ViewModel\ChangeCoin\ChangeCoinViewModel;
use App\Domain\Repository\ChangeCoinRepository;
use App\Domain\ValueObject\Coin;

final class UpdateChangeCoinQuantityUseCase
{
    public function __construct(
        private ChangeCoinRepository $repository,
    ) {
    }

    public function execute(UpdateChangeCoinQuantityDto $dto): ChangeCoinViewModel
    {
        $changeCoinCollection = $this->repository->findAllChangeCoins();
        $changeCoinCollection->setCoinAmount(
            Coin::from($dto->getCoinValue()),
            $dto->getQuantity()
        );
        $this->repository->persistChangeCoins($changeCoinCollection);

        return ChangeCoinViewModel::fromCoinValue($dto->getCoinValue(), $dto->getQuantity());
    }
}
