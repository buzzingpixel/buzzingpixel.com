<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\FetchPricesForSoftware;
use App\Context\Stripe\Entities\StripePriceCollection;
use Stripe\Price;
use Stripe\Product;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class FetchPricesForSoftwareOneTime implements FetchPricesForSoftware
{
    public function __construct(
        private Software $software,
        private StripeFetchProducts $stripeFetchProducts,
        private StripeFetchPrices $stripeFetchPrices,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function fetch(): StripePriceCollection
    {
        $software = $this->software;

        $slug = $software->slug();

        $product = $this->stripeFetchProducts->fetch()->filter(
            static fn (Product $p) => $p->metadata['slug'] === $slug,
        )->first();

        $price = $software->priceAsInt();

        // We're not returning this directly just in case there's more than one
        // (there never should be)
        $priceObj = $this->stripeFetchPrices->fetch([
            'product' => $product->id,
            'type' => 'one_time',
        ])
            ->filter(static fn (Price $p) => $p->unit_amount === $price)
            ->first();

        return new StripePriceCollection([$priceObj]);
    }
}
