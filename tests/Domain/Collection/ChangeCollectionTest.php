<?php

namespace Tests\sts\Domain\Collection;

use App\Domain\Collection\ChangeCoinCollection;
use App\Domain\ValueObject\Coin;
use PHPUnit\Framework\TestCase;

class ChangeCoinCollectionTest extends TestCase
{
    public function testAddCoin(): void
    {
        $collection = new ChangeCoinCollection();
        $coin = Coin::fromCurrencyValue(0.25);
        $collection->add($coin);
        $numberOfCoins = $collection->getCoinAmount($coin);
        $this->assertEquals(1, $numberOfCoins);

        $collection->add($coin);
        $numberOfCoins = $collection->getCoinAmount($coin);
        $this->assertEquals(2, $numberOfCoins);
    }

    public function testAddMultipleCoins(): void
    {
        $collection = new ChangeCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(0.10);
        $coin3 = Coin::fromCurrencyValue(0.05);
        $collection->add($coin1);
        $collection->add($coin2);
        $collection->add($coin2);
        $collection->add($coin3);

        $this->assertEquals(1, $collection->getCoinAmount($coin1));
        $this->assertEquals(2, $collection->getCoinAmount($coin2));
        $this->assertEquals(1, $collection->getCoinAmount($coin3));
    }

    public function testSetCoinAmount(): void
    {
        $collection = new ChangeCoinCollection();
        $coin = Coin::fromCurrencyValue(0.25);
        $collection->setCoinAmount($coin, 5);
        $this->assertEquals(5, $collection->getCoinAmount($coin));

        $collection->setCoinAmount($coin, 10);
        $this->assertEquals(10, $collection->getCoinAmount($coin));
    }

    public function testSetInvalidCoinAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $collection = new ChangeCoinCollection();
        $coin = Coin::fromCurrencyValue(1);
        $collection->setCoinAmount($coin, 5);
    }

    public function testCanProvideChange(): void
    {
        $collection = new ChangeCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(0.10);
        $coin3 = Coin::fromCurrencyValue(0.05);
        $collection->add($coin1);
        $collection->add($coin2);
        $collection->add($coin3);

        $this->assertTrue($collection->canProvideChange(0.40));
        $this->assertFalse($collection->canProvideChange(0.50));

        $collection->add($coin2);
        $this->assertTrue($collection->canProvideChange(0.50));
    }

    public function testProvideChange(): void
    {
        $collection = new ChangeCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(0.10);
        $coin3 = Coin::fromCurrencyValue(0.05);
        $collection->setCoinAmount($coin1, 10);
        $collection->setCoinAmount($coin2, 10);
        $collection->setCoinAmount($coin3, 10);

        $change = $collection->provideChange(0.65);

        $this->assertCount(4, $change);
        $this->assertEquals(0.25, $change[0]->asFloat());
        $this->assertEquals(0.25, $change[1]->asFloat());
        $this->assertEquals(0.10, $change[2]->asFloat());
        $this->assertEquals(0.05, $change[3]->asFloat());
    }

    public function testProvideChangeWithInsufficientCoins(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $collection = new ChangeCoinCollection();
        $coin1 = Coin::fromCurrencyValue(0.25);
        $coin2 = Coin::fromCurrencyValue(0.10);
        $coin3 = Coin::fromCurrencyValue(0.05);
        $collection->setCoinAmount($coin1, 1);
        $collection->setCoinAmount($coin2, 1);
        $collection->setCoinAmount($coin3, 1);

        $change = $collection->provideChange(0.65);
        print_r($change);
    }
}
