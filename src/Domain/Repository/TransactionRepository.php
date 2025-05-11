<?php

namespace App\Domain\Repository;

use App\Domain\Collection\InsertedCoinCollection;

interface TransactionRepository
{
    public function fetchTransaction(): InsertedCoinCollection;

    public function saveTransaction(InsertedCoinCollection $insertedCoinCollection): void;

    public function resetBalance(InsertedCoinCollection $insertedCoinCollection): void;
}
