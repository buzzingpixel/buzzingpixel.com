<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProductPricing;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;
use Throwable;

use function array_filter;
use function assert;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncProductPricingOneTimePrice implements SyncProductPricing
{
    public function __construct(
        private Product $product,
        private Software $software,
        private StripeClient $stripeClient,
        private AddProductPrice $addProductPrice,
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

        $allPrices = $this->stripeClient->prices->all([
            'product' => $this->product->id,
            'type' => 'one_time',
        ]);

        /** @psalm-suppress ArgumentTypeCoercion */
        $price = array_filter(
            $allPrices->data,
            static fn (Price $price) => $price->unit_amount === $priceInt,
        )[0] ?? null;

        assert(
            $price instanceof Price ||
            $price === null
        );

        if ($price !== null) {
            return;
        }

        $this->addProductPrice->add(
            productId: $this->product->id,
            unitAmountInCents: $priceInt,
        );
    }
}
