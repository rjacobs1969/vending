<?php

namespace App\Application\UseCase;

use App\Api\Dto\VendItemDto;
use App\Domain\Collection\ReturnCoinCollection;
use App\Domain\Repository\ChangeCoinRepository;
use App\Domain\Repository\ItemRepository;
use App\Domain\Repository\TransactionRepository;
use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Item\ItemViewModel;
use App\Application\ViewModel\Vend\VendViewModel;

final class VendItemUseCase
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private ChangeCoinRepository $changeCoinRepository,
        private ItemRepository $itemRepository,
    ) {
    }

    public function execute(VendItemDto $vendItemDto): VendViewModel
    {
        $insertedCoinCollection = $this->transactionRepository->fetchTransaction();
        $changeCoinCollection = $this->changeCoinRepository->findAllChangeCoins();
        $item = $this->itemRepository->findByName($vendItemDto->itemName());

        if ($item === null) {
            return VendViewModel::fromMessage(VendViewModel::MESSAGE_ITEM_NOT_FOUND);
        }
        if ($item->getQuantity() <= 0) {
            return VendViewModel::fromMessage(VendViewModel::MESSAGE_ITEM_NOT_AVAILABLE);
        }

        $itemPrice = $item->getPriceAsFloat();
        if ($insertedCoinCollection->totalAmount() < $itemPrice) {
            return VendViewModel::fromMessage(
                sprintf(
                    VendViewModel::MESSAGE_NOT_ENOUGH_MONEY,
                    number_format($itemPrice - $insertedCoinCollection->totalAmount(), 2, '.', '')
                )
            );
        }

        $changeNeeded = $insertedCoinCollection->totalAmount() - $itemPrice;
        if ($changeCoinCollection->canProvideChange($changeNeeded) === false) {
            return VendViewModel::fromMessage(VendViewModel::MESSAGE_NO_CHANGE);
        }

        $returnCoinCollection = new ReturnCoinCollection(
            $changeCoinCollection->provideChange($changeNeeded)
        );

        $item->decreaseQuantity(1);
        $this->itemRepository->persist($item);
        $this->changeCoinRepository->persistChangeCoins($changeCoinCollection);
        $this->transactionRepository->resetBalance($insertedCoinCollection);

        $message = $changeNeeded > 0
            ? VendViewModel::MESSAGE_ITEM_VENDED_WITH_COINS
            : VendViewModel::MESSAGE_ITEM_VENDED;

        return VendViewModel::fromCoinItemMessage(
            CoinListViewModel::fromCoinCollection($returnCoinCollection),
            ItemViewModel::fromItem($item),
            $message
        );
    }
}
