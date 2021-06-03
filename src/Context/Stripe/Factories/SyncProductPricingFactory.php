<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProductPricing;
use App\Context\Stripe\Services\AddProductPrice;
use App\Context\Stripe\Services\SyncProductPricingOneTimePrice;
use App\Context\Stripe\Services\SyncProductPricingSubscription;
use Stripe\Product;

class SyncProductPricingFactory
{
    public function __construct(
        private StripeFactory $stripeFactory,
        private AddProductPrice $addProductPrice,
    ) {
    }

    public function createSyncProductPricing(
        Product $product,
        Software $software,
    ): SyncProductPricing {
        if ($software->isSubscription()) {
            return new SyncProductPricingSubscription(
                product:  $product,
                software: $software,
                stripeClient: $this->stripeFactory->createStripeClient(),
                addProductPrice: $this->addProductPrice,
            );
        }

        return new SyncProductPricingOneTimePrice(
            product:  $product,
            software: $software,
            stripeClient: $this->stripeFactory->createStripeClient(),
            addProductPrice: $this->addProductPrice,
        );
    }
}
