<?php

namespace App\Application\ViewModel\Vend;

use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Item\ItemViewModel;
use Symfony\Component\HttpFoundation\Response;

class VendViewModel
{
    public const MESSAGE_COIN_ACCEPTED = 'Coin accepted, please select an item, insert more coins or return coins';
    public const MESSAGE_ITEM_VENDED = 'Item vended, please take your item';
    public const MESSAGE_ITEM_VENDED_WITH_COINS = 'Item vended, please take your item and coins';
    public const MESSAGE_RETURN_COINS = 'Coins returned, please take your coins';
    public const MESSAGE_NO_RETURN_COINS = 'No coins to return';
    public const MESSAGE_TOTAL_INSERTED_AMOUNT = 'Current total inserted amount';
    public const MESSAGE_ITEM_NOT_FOUND = 'Item not found in this vending machine';
    public const MESSAGE_NO_CHANGE = 'No change available, use exact amount';
    public const MESSAGE_NOT_ENOUGH_MONEY = 'Not enough money, please insert at least %s more';
    public const MESSAGE_ITEM_NOT_AVAILABLE = 'Item out of stock, please select another item';
    public const MESSAGE_COIN_NOT_ACCEPTED = 'Coin NOT accepted, coin returned, please insert only valid coins';

    public const FIELD_MESSAGE = 'message';
    public const FIELD_ITEM = 'item';
    public const FIELD_COINS = 'coins';
    public const FIELD_TOTAL_INSERTED_AMOUNT = 'total_inserted_amount';

    private const MESSAGE_TO_STATUS = [
        self::MESSAGE_ITEM_VENDED => Response::HTTP_OK,
        self::MESSAGE_RETURN_COINS => Response::HTTP_OK,
        self::MESSAGE_COIN_ACCEPTED => Response::HTTP_OK,
        self::MESSAGE_NO_RETURN_COINS => Response::HTTP_OK,
        self::MESSAGE_TOTAL_INSERTED_AMOUNT => Response::HTTP_OK,
        self::MESSAGE_ITEM_VENDED_WITH_COINS => Response::HTTP_OK,
        self::MESSAGE_NO_CHANGE => Response::HTTP_CONFLICT,
        self::MESSAGE_ITEM_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::MESSAGE_NOT_ENOUGH_MONEY => Response::HTTP_PAYMENT_REQUIRED,
        self::MESSAGE_COIN_NOT_ACCEPTED => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::MESSAGE_ITEM_NOT_AVAILABLE => Response::HTTP_SERVICE_UNAVAILABLE,
    ];

    public function __construct(
        public readonly ?CoinListViewModel $coins = null,
        public readonly ?ItemViewModel $item = null,
        public readonly ?string $message = null,
        public readonly ?float $totalInsertedAmount = null,
    ) {}

    public static function fromCoinItemMessage(
        ?CoinListViewModel $coins = null,
        ?ItemViewModel $item = null,
        ?string $message = null,
        ?float $totalInsertedAmount = null,
    ): self {
        return new self($coins, $item, $message, $totalInsertedAmount);
    }

    public static function fromMessage(string $message, ?float $totalInsertedAmount = null): self
    {
        return new self(null, null, $message, $totalInsertedAmount);
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->message) {
            $result[self::FIELD_MESSAGE] = $this->message;
        }
        if ($this->item) {
            $result[self::FIELD_ITEM] = $this->item->toString();
        }
        if ($this->coins) {
            $result[self::FIELD_COINS] = $this->coins->toArray();
        }
        if ($this->totalInsertedAmount) {
            $result[self::FIELD_TOTAL_INSERTED_AMOUNT] = round($this->totalInsertedAmount ,2);
        }

        return $result;
    }

    public function toStatus(): int
    {
        return self::MESSAGE_TO_STATUS[$this->message] ?? Response::HTTP_PAYMENT_REQUIRED;
    }
}
