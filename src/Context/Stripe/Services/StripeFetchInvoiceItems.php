<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\RequestCache\CacheItemPool as RequestCacheItemPool;
use App\Context\RequestCache\Entities\SessionCacheItem;
use App\Context\Stripe\Entities\StripeInvoiceItemCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\InvoiceItem;
use Stripe\StripeClient;

use function array_merge;
use function assert;
use function md5;
use function serialize;

class StripeFetchInvoiceItems
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
    public function fetch(array $params = []): StripeInvoiceItemCollection
    {
        $params = array_merge(self::DEFAULT_PARAMS, $params);

        $hash = md5(serialize($params));

        $cashKey = 'stripe_fetch_invoice__items_' . $hash;

        /** @noinspection PhpUnhandledExceptionInspection */
        if ($this->cacheItemPool->hasItem($cashKey)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $collection = $this->cacheItemPool->getItem($cashKey)->get();

            assert(
                $collection instanceof StripeInvoiceItemCollection
            );

            return $collection;
        }

        $collection = new StripeInvoiceItemCollection();

        $result = $this->stripeClient->invoiceItems->all($params);

        foreach ($result->autoPagingIterator() as $item) {
            assert($item instanceof InvoiceItem);

            $collection->add($item);
        }

        $this->cacheItemPool->save(new SessionCacheItem(
            $cashKey,
            $collection,
        ));

        return $collection;
    }
}
