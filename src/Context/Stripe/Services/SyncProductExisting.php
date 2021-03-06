<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProduct;
use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Stripe\Factories\SyncProductPricingFactory;
use Stripe\Product;
use Stripe\StripeClient;
use Throwable;

class SyncProductExisting implements SyncProduct
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private Product $product,
        private Software $software,
        private SyncProductPricingFactory $syncProductPricingFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function sync(): void
    {
        try {
            $response = $this->stripeClient->products->update(
                $this->product->id,
                [
                    'name' => $this->software->name(),
                    'active' => true,
                    'metadata' => [
                        'slug' => $this->software->slug(),
                        'id' => $this->software->id(),
                    ],
                ],
            );

            $this->syncProductPricingFactory->createSyncProductPricing(
                $response,
                $this->software,
            )->sync();
        } catch (Throwable) {
        }
    }
}
