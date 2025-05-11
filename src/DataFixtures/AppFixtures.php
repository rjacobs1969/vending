<?php

namespace App\DataFixtures;

use App\Domain\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $item = new Item('Snickers', 175, 10);

        $item->setId(1);
        $manager->persist($item);
        $manager->flush();
    }
}
