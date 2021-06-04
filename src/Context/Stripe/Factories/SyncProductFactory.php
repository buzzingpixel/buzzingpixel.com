<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProduct;
use App\Context\Stripe\Services\SyncProductAddNew;
use App\Context\Stripe\Services\SyncProductArchive;
use App\Context\Stripe\Services\SyncProductExisting;
use App\Context\Stripe\Services\SyncProductNoOp;
use Stripe\Product;

class SyncProductFactory
{
    public function __construct(
        private StripeFactory $stripeFactory,
        private SyncProductPricingFactory $syncProductPricingFactory,
    ) {
    }

    public function createSyncProduct(
        ?Product $product = null,
        ?Software $software = null,
    ): SyncProduct {
        if (
            $product !== null &&
            ($software === null || ! $software->isForSale())
        ) {
            return new SyncProductArchive(
                stripeFactory: $this->stripeFactory,
                product: $product,
            );
        }

        if ($product === null && $software !== null) {
            return new SyncProductAddNew(
                stripeFactory: $this->stripeFactory,
                software: $software,
                syncProductPricingFactory: $this->syncProductPricingFactory,
            );
        }

        if ($product !== null && $software !== null) {
            return new SyncProductExisting(
                stripeFactory: $this->stripeFactory,
                product: $product,
                software: $software,
                syncProductPricingFactory: $this->syncProductPricingFactory,
            );
        }

        return new SyncProductNoOp();
    }
}
