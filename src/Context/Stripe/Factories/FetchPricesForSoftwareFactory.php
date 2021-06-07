<?php

declare(strict_types=1);

namespace App\Context\Stripe\Factories;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Contracts\FetchPricesForSoftware;
use App\Context\Stripe\Services\FetchPricesForSoftwareOneTime;
use App\Context\Stripe\Services\FetchPricesForSoftwareSubscription;
use App\Context\Stripe\Services\StripeFetchPrices;
use App\Context\Stripe\Services\StripeFetchProducts;

class FetchPricesForSoftwareFactory
{
    public function __construct(
        private StripeFetchProducts $stripeFetchProducts,
        private StripeFetchPrices $stripeFetchPrices,
    ) {
    }

    public function createFetchPricesForSoftware(
        Software $software,
    ): FetchPricesForSoftware {
        if ($software->isSubscription()) {
            return new FetchPricesForSoftwareSubscription(
                software: $software,
                stripeFetchProducts: $this->stripeFetchProducts,
                stripeFetchPrices: $this->stripeFetchPrices,
            );
        }

        return new FetchPricesForSoftwareOneTime(
            software: $software,
            stripeFetchProducts: $this->stripeFetchProducts,
            stripeFetchPrices: $this->stripeFetchPrices,
        );
    }
}
