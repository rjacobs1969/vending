<?php

namespace Tests\sts\Domain\Collection;

use App\Domain\Collection\InsertedCoinCollection;
use App\Domain\ValueObject\Coin;
use PHPUnit\Framework\TestCase;

class InsertedCoinCollectionTest extends TestCase
{
    public function testAddCoin(): void
    {
        $collection = new InsertedCoinCollection();
        $coin = Coin::fromCurrencyValue(0.25);
        $collection->add($coin);

        $this->assertCount(1, $collection);
        $this->assertEquals(0.25, $collection->totalAmount());
    }

    public function testAddMultipleCoins(): void
    {
        $collection = new InsertedCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(1.00);
        $coin3 = Coin::fromCurrencyValue(0.25);
        $collection->add($coin1);
        $collection->add($coin2);
        $collection->add($coin3);

        $this->assertCount(3, $collection);
        $this->assertEquals(1.5, $collection->totalAmount());
    }

    public function testEmptyCollection(): void
    {
        $collection = new InsertedCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $collection->add($coin1);
        $this->assertCount(1, $collection);

        $collection->empty();
        $this->assertCount(0, $collection);
        $this->assertEquals(0.00, $collection->totalAmount());
    }

    public function testTotalAmount(): void
    {
        $collection = new InsertedCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(1.00);
        $collection->add($coin1);
        $collection->add($coin2);

        $this->assertEquals(1.25, $collection->totalAmount());
    }

    public function testGetCoins(): void
    {
        $collection = new InsertedCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(1.00);
        $collection->add($coin1);
        $collection->add($coin2);

        $this->assertEquals(['0.25', '1.00'], $collection->getCoins());
    }

    public function testToJson(): void
    {
        $collection = new InsertedCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(1.00);
        $collection->add($coin1);
        $collection->add($coin2);

        $this->assertEquals('[25,100]', $collection->toJson());
    }

    public function testFromJson(): void
    {
        $json = '[25,100,10]';
        $collection = InsertedCoinCollection::fromJson($json);

        $this->assertCount(3, $collection);
        $this->assertEquals(0.25, $collection->getCoins()[0]);
        $this->assertEquals(1.00, $collection->getCoins()[1]);
        $this->assertEquals(0.10, $collection->getCoins()[2]);
    }
}
