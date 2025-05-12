<?php

namespace App\Domain\Repository;

use App\Domain\Collection\ChangeCoinCollection;

interface ChangeCoinRepository
{
    public function findAllChangeCoins(): ChangeCoinCollection;

    public function persistChangeCoins(ChangeCoinCollection $changeCoinCollection): void;
}
