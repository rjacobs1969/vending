<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entity\Item;
use App\Domain\Repository\ItemRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineItemRepository extends ServiceEntityRepository implements ItemRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findById(int $id): ?Item
    {
        return $this->find($id);
    }

    public function findByName(string $itemName): ?Item
    {
        return $this->findOneBy(['name' => $itemName]);
    }

    public function persist(Item $item): void
    {
        $this->getEntityManager()->persist($item);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(int $id): void
    {
        $item = $this->find($id);

        if ($item) {
            $this->getEntityManager()->remove($item);
            $this->flush();
        }
    }
}
