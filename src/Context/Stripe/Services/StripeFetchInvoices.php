<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\RequestCache\CacheItemPool as RequestCacheItemPool;
use App\Context\RequestCache\Entities\SessionCacheItem;
use App\Context\Stripe\Entities\StripeInvoiceCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\Invoice;
use Stripe\StripeClient;

use function array_merge;
use function assert;
use function md5;
use function serialize;

class StripeFetchInvoices
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
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function fetch(array $params = []): StripeInvoiceCollection
    {
        $params = array_merge(self::DEFAULT_PARAMS, $params);

        $hash = md5(serialize($params));

        $cashKey = 'stripe_fetch_invoices_' . $hash;

        /** @noinspection PhpUnhandledExceptionInspection */
        if ($this->cacheItemPool->hasItem($cashKey)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $collection = $this->cacheItemPool->getItem($cashKey)->get();

            assert($collection instanceof StripeInvoiceCollection);

            return $collection;
        }

        $collection = new StripeInvoiceCollection();

        $result = $this->stripeClient->invoices->all($params);

        foreach ($result->autoPagingIterator() as $item) {
            assert($item instanceof Invoice);

            $collection->add($item);
        }

        $this->cacheItemPool->save(new SessionCacheItem(
            $cashKey,
            $collection,
        ));

        return $collection;
    }
}
