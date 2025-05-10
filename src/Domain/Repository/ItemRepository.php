<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Item;

interface ItemRepository
{
    /**
     * @return Item[]
     */
    public function findAll(): array;

    public function findById(int $id): ?Item;

    public function persist(Item $item): void;

    public function flush(): void;

    public function remove(int $id): void;
}
