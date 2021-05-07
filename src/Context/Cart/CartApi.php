<?php

declare(strict_types=1);

namespace App\Context\Cart;

use App\Context\Cart\Entities\Cart;
use App\Context\Cart\Services\FetchCurrentUserCart;
use App\Context\Cart\Services\SaveCart;
use App\Payload\Payload;

class CartApi
{
    public function __construct(
        private FetchCurrentUserCart $fetchCurrentUserCart,
        private SaveCart $saveCart,
    ) {
    }

    public function fetchCurrentUserCart(): Cart
    {
        return $this->fetchCurrentUserCart->fetch();
    }

    public function saveCart(Cart $cart): Payload
    {
        return $this->saveCart->save($cart);
    }
}
