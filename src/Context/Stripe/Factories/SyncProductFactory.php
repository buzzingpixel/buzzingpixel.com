<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProduct;
use App\Context\Stripe\Services\SyncProductAddNew;
use App\Context\Stripe\Services\SyncProductArchive;
use App\Context\Stripe\Services\SyncProductExisting;
use LogicException;
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
                product: $product,
                stripeClient: $this->stripeFactory->createStripeClient(),
            );
        }

        if ($product === null && $software !== null) {
            return new SyncProductAddNew(
                software: $software,
                stripeClient: $this->stripeFactory->createStripeClient(),
                syncProductPricingFactory: $this->syncProductPricingFactory,
            );
        }

        if ($product !== null && $software !== null) {
            return new SyncProductExisting(
                product: $product,
                software: $software,
                stripeClient: $this->stripeFactory->createStripeClient(),
                syncProductPricingFactory: $this->syncProductPricingFactory,
            );
        }

        throw new LogicException(
            'Either $product or $software must be defined'
        );
    }
}
