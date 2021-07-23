<?php

declare(strict_types=1);

namespace App\Context\Cart\Services;

use App\Context\Cart\Entities\Cart;

class ClearCart
{
    public function __construct(private SaveCart $saveCart)
    {
    }

    public function clear(Cart $cart): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->saveCart->save($cart->withCartItems([]));
    }
}
