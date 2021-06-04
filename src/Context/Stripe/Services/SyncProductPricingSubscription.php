<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\SyncProductPricing;
use Stripe\Price;
use Stripe\Product;
use Throwable;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class SyncProductPricingSubscription implements SyncProductPricing
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
            $this->syncFixedPrice();

            $this->syncSubscriptionPrice();
        } catch (Throwable) {
        }
    }

    private function syncFixedPrice(): void
    {
        $fixedInt = $this->software->priceLessSubscriptionAsInt();

        $allFixedPrices = $this->stripeFetchPrices->fetch([
            'product' => $this->product->id,
            'type' => 'one_time',
        ]);

        $fixedPrice = $allFixedPrices->filter(
            static fn (Price $price) => $price->unit_amount === $fixedInt
        )->firstOrNull();

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

        $allSubPrices = $this->stripeFetchPrices->fetch([
            'product' => $this->product->id,
            'type' => 'recurring',
        ]);

        $subPrice = $allSubPrices->filter(
            static fn (Price $price) => $price->unit_amount === $priceInt
        )->firstOrNull();

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
