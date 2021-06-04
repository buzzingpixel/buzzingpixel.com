<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Contracts\SyncProduct;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\Product;
use Stripe\StripeClient;
use Throwable;

class SyncProductArchive implements SyncProduct
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private Product $product,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function sync(): void
    {
        try {
            $prodArray = $this->product->toArray();

            $this->stripeClient->products->update(
                $this->product->id,
                [
                    'name' => $prodArray['name'],
                    'active' => false,
                    'metadata' => $prodArray['metadata'],
                ],
            );
        } catch (Throwable) {
        }
    }
}
