<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncProduct;
use Stripe\Product;
use Stripe\StripeClient;

class SyncProductArchive implements SyncProduct
{
    public function __construct(
        private Product $product,
        private StripeClient $stripeClient,
    ) {
    }

    public function sync(): void
    {
        $prodArray = $this->product->toArray();

        $this->stripeClient->products->update(
            $this->product->id,
            [
                'name' => $prodArray['name'],
                'active' => false,
                'metadata' => $prodArray['metadata'],
            ],
        );
    }
}
