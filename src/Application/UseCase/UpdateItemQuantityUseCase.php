<?php

namespace App\Application\UseCase;

use App\Api\Dto\UpdateItemQuantityDto;
use App\Application\ViewModel\Item\ItemViewModel;
use App\Domain\Repository\ItemRepository;

final class UpdateItemQuantityUseCase
{
    public function __construct(private ItemRepository $repository) {}

    public function execute(UpdateItemQuantityDto $updateItemQuantityDto): ?ItemViewModel
    {
        $item = $this->repository->findByName($updateItemQuantityDto->getName());

        if ($item === null) {
            return null;
        }

        $item->setQuantity($updateItemQuantityDto->quantity);
        $this->repository->persist($item);
        $this->repository->flush();

        return ItemViewModel::fromItem($item);
    }
}
