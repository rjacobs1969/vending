<?php

namespace App\Application\ViewModel\Vend;

use App\Application\ViewModel\Coin\CoinListViewModel;
use App\Application\ViewModel\Item\ItemViewModel;
use Symfony\Component\HttpFoundation\Response;

class VendViewModel
{
    public const MESSAGE_ITEM_NOT_FOUND = 'Item not found in this vending machine';
    public const MESSAGE_NOT_ENOUGH_MONEY = 'Not enough money, please insert at least %s more';
    public const MESSAGE_ITEM_NOT_AVAILABLE = 'Item out of stock, please select another item';
    public const MESSAGE_NO_CHANGE = 'No change available, use exact amount';
    public const MESSAGE_ITEM_VENDED = 'Item vended, please take your item';
    public const MESSAGE_ITEM_VENDED_WITH_COINS = 'Item vended, please take your item and coins';
    public const MESSAGE_RETURN_COINS = 'Coins returned, please take your coins';
    public const MESSAGE_NO_RETURN_COINS = 'No coins to return';

    private const MESSAGE_TO_STATUS = [
        self::MESSAGE_ITEM_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::MESSAGE_NOT_ENOUGH_MONEY => Response::HTTP_PAYMENT_REQUIRED,
        self::MESSAGE_ITEM_NOT_AVAILABLE => Response::HTTP_SERVICE_UNAVAILABLE,
        self::MESSAGE_NO_CHANGE => Response::HTTP_CONFLICT,
        self::MESSAGE_ITEM_VENDED => Response::HTTP_OK,
        self::MESSAGE_ITEM_VENDED_WITH_COINS => Response::HTTP_OK,
        self::MESSAGE_RETURN_COINS => Response::HTTP_OK,
        self::MESSAGE_NO_RETURN_COINS => Response::HTTP_OK,
    ];

    public function __construct(
        public readonly ?CoinListViewModel $coins = null,
        public readonly ?ItemViewModel $item = null,
        public readonly ?string $message = null,
    ) {}

    public static function fromCoinItemMessage(
        ?CoinListViewModel $coins = null,
        ?ItemViewModel $item = null,
        ?string $message = null,
    ): self {
        return new self($coins, $item, $message);
    }

    public static function fromMessage( string $message ): self
    {
        return new self(null, null, $message);
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->message) {
            $result['message'] = $this->message;
        }
        if ($this->item) {
            $result['item'] = $this->item->toString();
        }
        if ($this->coins) {
            $result['coins'] = $this->coins->toArray();
        }

        return $result;
    }

    public function toStatus(): int
    {
        //return 500;
        return self::MESSAGE_TO_STATUS[$this->message] ?? Response::HTTP_PAYMENT_REQUIRED;
    }
}
