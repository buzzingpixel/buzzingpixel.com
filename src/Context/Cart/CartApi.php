<?php

declare(strict_types=1);

namespace App\Context\Cart;

use App\Context\Cart\Entities\Cart;
use App\Context\Cart\Services\FetchCurrentUserCart;

class CartApi
{
    public function __construct(
        private FetchCurrentUserCart $fetchCurrentUserCart,
    ) {
    }

    public function fetchCurrentUserCart(): Cart
    {
        return $this->fetchCurrentUserCart->fetch();
    }
}
