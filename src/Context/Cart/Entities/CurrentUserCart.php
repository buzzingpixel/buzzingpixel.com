<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

class CurrentUserCart
{
    public function __construct(private Cart $cart)
    {
    }

    public function cart(): Cart
    {
        return $this->cart;
    }
}
