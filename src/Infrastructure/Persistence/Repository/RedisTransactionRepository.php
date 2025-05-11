<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Collection\InsertedCoinCollection;
use App\Domain\Repository\TransactionRepository;
use App\Shared\Repository\EphemeralPersistence;

class RedisTransactionRepository implements TransactionRepository
{
    private const INSERTED_COINS_KEY = 'inserted_coins';
    private string $key;

    public function __construct(
        private EphemeralPersistence $repository,
        private string $environment = 'prod'
    ) {
        $this->key = sprintf('%s:%s', self::INSERTED_COINS_KEY, $this->environment);
    }

    public function fetchTransaction(): InsertedCoinCollection
    {
        $jsonData = $this->repository->get($this->key);

        return InsertedCoinCollection::fromJson($jsonData);
    }

    public function saveTransaction(InsertedCoinCollection $insertedCoinCollection): void
    {
        $jsonData = $insertedCoinCollection->toJson();
        $this->repository->set($this->key, $jsonData);
    }

    public function resetBalance(InsertedCoinCollection $insertedCoinCollection): void
    {
        $insertedCoinCollection->empty();
        $this->saveTransaction($insertedCoinCollection);
    }
}
