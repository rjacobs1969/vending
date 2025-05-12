<?php

namespace App\DataFixtures;

use App\Domain\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $water = (new Item('Water', 65, 0))->setId(1);
        $juice = (new Item('Juice', 100, 0))->setId(2);
        $soda = (new Item('Soda', 150, 0))->setId(3);

        $manager->persist($water);
        $manager->persist($juice);
        $manager->persist($soda);
        $manager->flush();
    }
}
