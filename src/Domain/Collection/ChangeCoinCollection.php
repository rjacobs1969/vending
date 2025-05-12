<?php

namespace App\Domain\Collection;

use App\Domain\ValueObject\Coin;
use JsonSerializable;

final class ChangeCoinCollection
{
    private array $changeCoinsAvailable = [
        25 => 0,
        10 => 0,
        5 => 0,
    ];

    public function __construct(array $coins = [])
    {
        foreach ($coins as $coin) {
            $this->validateCoinValue($coin);
            $this->add($coin);
        }
    }

    public function add(Coin $coin): void
    {
        $this->validateCoinValue($coin);
        $this->setCoinAmount($coin, $this->getCoinAmount($coin) + 1);
    }

    public function getCoinAmount(Coin $coin): int
    {
        $this->validateCoinValue($coin);
        return $this->changeCoinsAvailable[$coin->value];
    }

    public function setCoinAmount(Coin $coin, int $amount): void
    {
        $this->validateCoinValue($coin);
        $this->validateCoinAmount($amount);
        $this->changeCoinsAvailable[$coin->value] = $amount;
    }

    public function provideChange(float $amount): array
    {
        $coins = [];

        foreach ($this->calculateBestChange($amount) as $coinValue => $coinsNeeded) {
            $coin = Coin::from($coinValue);
            for ($i = 0; $i < $coinsNeeded; $i++) {
                $coins[] = $coin;
            }
            $this->setCoinAmount($coin, $this->getCoinAmount($coin) - $coinsNeeded);
        }

        return $coins;
    }

    public function canProvideChange(float $amount): bool
    {
        $remainingAmount = (int)($amount * 100);

        foreach ($this->calculateBestChange($amount) as $coinValue => $coinsNeeded) {
            $remainingAmount -= $coinValue * $coinsNeeded;
        }

        return $remainingAmount === 0;
    }

    private function calculateBestChange(float $amount): array
    {
        $change = [];
        $remainingAmount = (int) ($amount * 100); // Convert to cents

        foreach ($this->changeCoinsAvailable as $coinValue => $available) {
            if ($available <= 0 || $remainingAmount <= 0) {
                continue;
            }

            // Calculate how many coins we can use, but not more than we have
            $coinsUsed = min(
                (int)($remainingAmount / $coinValue), // How many coins we need
                $available // How many coins we have
            );

            if ($coinsUsed > 0) {
                $change[$coinValue] = $coinsUsed;
                $remainingAmount -= $coinsUsed * $coinValue;
            }
        }

        return $change;
    }



    public function provideChangeOrd(float $amount): array
    {
        $remainingAmount = (int) ($amount * 100); // Convert to cents
        $change = [];

        foreach ($this->changeCoinsAvailable as $coinValue => $available) {
            if ($available <= 0 || $remainingAmount <= 0) {
                continue;
            }

            $coinsNeeded = min(
                (int)($remainingAmount / $coinValue), // How many coins we need
                $available // How many coins we have
            );

            if ($coinsNeeded > 0) {
                $coin = Coin::from($coinValue);
                // Add the coin multiple times based on coinsNeeded
                for ($i = 0; $i < $coinsNeeded; $i++) {
                    $change[] = $coin;
                }
                $this->setCoinAmount($coin, $available - $coinsNeeded);
                $remainingAmount -= $coinsNeeded * $coinValue;
            }
        }

        return $change;
    }

    public function toJson(): string
    {
        return json_encode($this->changeCoinsAvailable);
    }

    public static function fromJson(string $json): static
    {
        $instance = new static();
        $data = json_decode($json, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            foreach ($data as $coinName => $amount) {
                $coin = Coin::tryFrom($coinName);
                $instance->setCoinAmount($coin, $amount);
            }
        }

        return $instance;
    }

    private function validateCoinValue(Coin $coin): void
    {
        if (!in_array($coin->value, array_keys($this->changeCoinsAvailable), true)) {
            throw new \InvalidArgumentException("Invalid coin type: {$coin->value}");
        }
    }

    private function validateCoinAmount(int $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Amount cannot be negative.");
        }
    }

}
