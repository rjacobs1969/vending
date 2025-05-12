<?php

namespace App\Application\UseCase;

use App\Api\Dto\VendItemDto;
use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Item\ItemViewModel;
use App\Application\ViewModel\Vend\VendViewModel;
use App\Domain\Collection\ReturnCoinCollection;
use App\Domain\Repository\ItemRepository;
use App\Domain\Repository\TransactionRepository;

final class VendItemUseCase
{
    public function __construct(
        private TransactionRepository $repository,
        private ItemRepository $itemRepository,
    ) {
    }

    public function execute(VendItemDto $vendItemDto): VendViewModel
    {
        $insertedCoinCollection = $this->repository->fetchTransaction();
        $returnCoinCollection = new ReturnCoinCollection();
        $item = $this->itemRepository->findByName($vendItemDto->itemName());

        if ($item === null) {
            return VendViewModel::fromMessage(VendViewModel::MESSAGE_ITEM_NOT_FOUND);
        }

        $itemPrice = $item->getPrice();
        if ($insertedCoinCollection->totalAmount() < $itemPrice / 100) {
            return VendViewModel::fromMessage(VendViewModel::MESSAGE_NOT_ENOUGH_MONEY );
        }

       // $returnCoinCollection = $insertedCoinCollection->returnCoins($itemPrice);
        //$this->repository->saveTransaction($insertedCoinCollection);

        return VendViewModel::fromCoinItemMessage(
            CoinListViewModel::fromCoinCollection($returnCoinCollection),
            ItemViewModel::fromItem($item),
            VendViewModel::MESSAGE_ITEM_VENDED
        );
    }

}
