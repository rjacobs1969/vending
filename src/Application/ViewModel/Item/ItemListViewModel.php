<?php

namespace App\Application\ViewModel\Item;

use App\Domain\Entity\Item;

class ItemListViewModel
{
    public function __construct(private array $itemViewModels)
    {
    }

    public function toArray(): array
    {
        return array_map(
            fn (ItemViewModel $item) => $item->toArray(),
            array_filter(
                $this->itemViewModels,
                fn ($item) => $item instanceof ItemViewModel
            )
        );
    }

    public static function fromItemList(array $items): self
    {
        $itemViewModels = array_map(
            fn (Item $item) => ItemViewModel::fromItem($item),
            array_filter(
                $items,
                fn ($item) => $item instanceof Item
            )
        );

        return new self($itemViewModels);
    }
}
