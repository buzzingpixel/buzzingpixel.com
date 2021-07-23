<?php

declare(strict_types=1);

namespace App\Context\Cart;

use App\Context\Cart\Entities\Cart;
use App\Context\Cart\Services\ClearCart;
use App\Context\Cart\Services\FetchCurrentUserCart;
use App\Context\Cart\Services\SaveCart;
use App\Payload\Payload;

class CartApi
{
    public function __construct(
        private SaveCart $saveCart,
        private ClearCart $clearCart,
        private FetchCurrentUserCart $fetchCurrentUserCart,
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

    public function clearCart(Cart $cart): void
    {
        $this->clearCart->clear($cart);
    }

    public function clearCurrentUserCart(): void
    {
        $this->clearCart->clear($this->fetchCurrentUserCart());
    }
}
