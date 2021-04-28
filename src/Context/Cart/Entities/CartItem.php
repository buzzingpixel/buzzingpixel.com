<?php

declare(strict_types=1);

namespace App\Context\Cart\Entities;

use App\Context\Software\Entities\Software;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\Quantity;
use App\EntityPropertyTraits\Slug;

class CartItem
{
    use Id;
    use Quantity;
    use Slug;

    private Cart $cart;
    private Software $software;

    public function cart(): Cart
    {
        return $this->cart;
    }

    /**
     * @return $this
     */
    public function withCart(Cart $cart): self
    {
        $clone = clone $this;

        $clone->cart = $cart;

        return $clone;
    }

    public function software(): Software
    {
        return $this->software;
    }

    /**
     * @return $this
     */
    public function withSoftware(Software $software): self
    {
        $clone = clone $this;

        $clone->software = $software;

        return $clone;
    }
}
