<?php

declare(strict_types=1);

namespace App\Context\Software\Services;

use App\Context\Software\Entities\Software;
use App\Context\Stripe\Factories\StripeFactory;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Stripe\StripeClient;

use function assert;

class FetchSoftwareByStripePriceId
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private FetchOneSoftware $fetchOneSoftware,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function fetch(string $stripeId): Software
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $price = $this->stripeClient->prices->retrieve($stripeId);

        /** @noinspection PhpUnhandledExceptionInspection */
        $product = $this->stripeClient->products->retrieve(
            (string) $price->product
        );

        /** @phpstan-ignore-next-line  */
        $localId = (string) $product->metadata->id;

        $software = $this->fetchOneSoftware->fetch(
            (new SoftwareQueryBuilder())
                ->withId($localId),
        );

        assert($software instanceof Software);

        return $software;
    }
}
