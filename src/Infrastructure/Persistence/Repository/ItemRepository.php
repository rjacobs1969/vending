<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function persist(Item $item): void
    {
        $this->getEntityManager()->persist($item);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(int $id): ?Item
    {
        $item = $this->find($id);

        if ($item) {
            $this->getEntityManager()->remove($item);
            $this->flush();
        }

        return $item;
    }
}
