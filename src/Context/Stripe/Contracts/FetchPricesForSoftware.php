<?php

declare(strict_types=1);

namespace App\Context\Stripe\Contracts;

use App\Context\Stripe\Entities\StripePriceCollection;

interface FetchPricesForSoftware
{
    /** @phpstan-ignore-next-line  */
    public function fetch(): StripePriceCollection;
}
