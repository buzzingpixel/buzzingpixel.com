<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Entities\StripePriceCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\Price;
use Stripe\StripeClient;

use function array_merge;
use function assert;

class StripeFetchPrices
{
    private const DEFAULT_PARAMS = ['limit' => 999999];

    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetch(array $params = []): StripePriceCollection
    {
        $collection = new StripePriceCollection();

        $result = $this->stripeClient->prices
            ->all(array_merge(self::DEFAULT_PARAMS, $params));

        foreach ($result->autoPagingIterator() as $item) {
            assert($item instanceof Price);

            $collection->add($item);
        }

        return $collection;
    }
}
