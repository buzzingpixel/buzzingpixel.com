<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProduct;
use App\Context\Stripe\Factories\SyncProductPricingFactory;
use Stripe\StripeClient;

class SyncProductAddNew implements SyncProduct
{
    public function __construct(
        private Software $software,
        private StripeClient $stripeClient,
        private SyncProductPricingFactory $syncProductPricingFactory,
    ) {
    }

    public function sync(): void
    {
        $response = $this->stripeClient->products->create([
            'name' => $this->software->name(),
            'active' => true,
            'metadata' => [
                'slug' => $this->software->slug(),
            ],
        ]);

        $this->syncProductPricingFactory->createSyncProductPricing(
            $response,
            $this->software,
        )->sync();
    }
}
