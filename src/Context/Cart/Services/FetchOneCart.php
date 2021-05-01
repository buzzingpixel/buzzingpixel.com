<?php

declare(strict_types=1);

namespace App\Context\Cart\Services;

use App\Context\Cart\Entities\Cart;
use App\Persistence\QueryBuilders\Cart\CartQueryBuilder;

class FetchOneCart
{
    public function __construct(private FetchCarts $fetchCarts)
    {
    }

    public function fetch(CartQueryBuilder $queryBuilder): ?Cart
    {
        $cartCollection = $this->fetchCarts->fetch(
            $queryBuilder->withLimit(1),
        );

        if ($cartCollection->count() < 1) {
            return null;
        }

        return $cartCollection->first();
    }
}
