<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProductPricing;
use Stripe\Price;
use Stripe\Product;
use Throwable;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncProductPricingOneTimePrice implements SyncProductPricing
{
    public function __construct(
        private Product $product,
        private Software $software,
        private AddProductPrice $addProductPrice,
        private StripeFetchPrices $stripeFetchPrices,
    ) {
    }

    public function sync(): void
    {
        try {
            $this->syncInner();
        } catch (Throwable) {
        }
    }

    public function syncInner(): void
    {
        $priceInt = $this->software->priceAsInt();

        $allPrices = $this->stripeFetchPrices->fetch([
            'product' => $this->product->id,
            'type' => 'one_time',
        ]);

        $price = $allPrices->filter(
            static fn (Price $price) => $price->unit_amount === $priceInt
        )->firstOrNull();

        if ($price !== null) {
            return;
        }

        $this->addProductPrice->add(
            productId: $this->product->id,
            unitAmountInCents: $priceInt,
        );
    }
}
