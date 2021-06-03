<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProductPricing;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;

use function array_filter;
use function assert;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncProductPricingSubscription implements SyncProductPricing
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
        $this->syncFixedPrice();

        $this->syncSubscriptionPrice();
    }

    private function syncFixedPrice(): void
    {
        $fixedInt = $this->software->priceLessSubscriptionAsInt();

        $allFixedPrices = $this->stripeClient->prices->all([
            'product' => $this->product->id,
            'type' => 'one_time',
        ]);

        /** @psalm-suppress ArgumentTypeCoercion */
        $fixedPrice = array_filter(
            $allFixedPrices->data,
            static fn (Price $price) => $price->unit_amount === $fixedInt,
        )[0] ?? null;

        assert(
            $fixedPrice instanceof Price ||
            $fixedPrice === null
        );

        if ($fixedPrice !== null) {
            return;
        }

        $this->addProductPrice->add(
            productId: $this->product->id,
            unitAmountInCents: $fixedInt,
        );
    }

    private function syncSubscriptionPrice(): void
    {
        $priceInt = $this->software->renewalPriceAsInt();

        $allSubPrices = $this->stripeClient->prices->all([
            'product' => $this->product->id,
            'type' => 'recurring',
        ]);

        /** @psalm-suppress ArgumentTypeCoercion */
        $subPrice = array_filter(
            $allSubPrices->data,
            static fn (Price $price) => $price->unit_amount === $priceInt,
        )[0] ?? null;

        assert(
            $subPrice instanceof Price ||
            $subPrice === null
        );

        if ($subPrice !== null) {
            return;
        }

        $this->addProductPrice->add(
            productId: $this->product->id,
            unitAmountInCents: $priceInt,
            isRecurring: true,
        );
    }
}
