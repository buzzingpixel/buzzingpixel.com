<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProductPricing;
use App\Context\Stripe\Services\AddProductPrice;
use App\Context\Stripe\Services\StripeFetchPrices;
use App\Context\Stripe\Services\SyncProductPricingOneTimePrice;
use App\Context\Stripe\Services\SyncProductPricingSubscription;
use Stripe\Product;

class SyncProductPricingFactory
{
    public function __construct(
        private AddProductPrice $addProductPrice,
        private StripeFetchPrices $stripeFetchPrices,
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
                addProductPrice: $this->addProductPrice,
                stripeFetchPrices: $this->stripeFetchPrices,
            );
        }

        return new SyncProductPricingOneTimePrice(
            product:  $product,
            software: $software,
            addProductPrice: $this->addProductPrice,
            stripeFetchPrices: $this->stripeFetchPrices,
        );
    }
}
