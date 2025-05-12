<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Collection\ChangeCoinCollection;
use App\Domain\Repository\ChangeCoinRepository;
use App\Shared\Repository\EphemeralPersistence;

class RedisChangeCoinRepository implements ChangeCoinRepository
{
    private const CHANGE_COINS_KEY = 'change_coins';

    private string $key;

    public function __construct(
        private EphemeralPersistence $repository,
        private string $environment = 'prod'
    ) {
        $this->key = sprintf('%s:%s', self::CHANGE_COINS_KEY, $this->environment);
    }

    public function findAllChangeCoins(): ChangeCoinCollection
    {
        $jsonData = $this->repository->get($this->key);
        return ChangeCoinCollection::fromJson($jsonData);
    }

    public function persistChangeCoins(ChangeCoinCollection $changeCoinCollection): void
    {
        $jsonData = $changeCoinCollection->toJson();
        $this->repository->set($this->key, $jsonData);
    }
}
