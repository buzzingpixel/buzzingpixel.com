<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\RequestCache\CacheItemPool as RequestCacheItemPool;
use App\Context\RequestCache\Entities\SessionCacheItem;
use App\Context\Stripe\Entities\StripeTaxRateCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\StripeClient;
use Stripe\TaxRate;

use function array_merge;
use function assert;
use function md5;
use function serialize;

class StripeFetchTaxRates
{
    private const DEFAULT_PARAMS = ['limit' => 999999];

    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private RequestCacheItemPool $cacheItemPool,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    /**
     * @param mixed[] $params
     *
     * @phpstan-ignore-next-line
     */
    public function fetch(array $params = []): StripeTaxRateCollection
    {
        $params = array_merge(self::DEFAULT_PARAMS, $params);

        $hash = md5(serialize($params));

        $cashKey = 'stripe_fetch_tax_rates_' . $hash;

        if ($this->cacheItemPool->hasItem($cashKey)) {
            $collection = $this->cacheItemPool->getItem($cashKey)->get();

            assert($collection instanceof StripeTaxRateCollection);

            return $collection;
        }

        $collection = new StripeTaxRateCollection();

        $result = $this->stripeClient->taxRates->all($params);

        foreach ($result->autoPagingIterator() as $item) {
            assert($item instanceof TaxRate);

            $collection->add($item);
        }

        $this->cacheItemPool->save(new SessionCacheItem(
            $cashKey,
            $collection,
        ));

        return $collection;
    }
}
