<?php

namespace App\Application\UseCase;

use App\Application\ViewModel\Item\ItemListViewModel;
use App\Domain\Repository\ItemRepository;

final class ListItemsUseCase
{
    public function __construct(
        private ItemRepository $repository,
    ) {
    }

    public function execute(): ItemListViewModel
    {
        $itemList = $this->repository->findAll();

        return ItemListViewModel::fromItemList($itemList);
    }
}
