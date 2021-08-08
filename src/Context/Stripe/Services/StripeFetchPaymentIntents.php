<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\RequestCache\CacheItemPool as RequestCacheItemPool;
use App\Context\RequestCache\Entities\SessionCacheItem;
use App\Context\Stripe\Entities\StripePaymentIntentsCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

use function array_merge;
use function assert;
use function md5;
use function serialize;

class StripeFetchPaymentIntents
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
    public function fetch(array $params = []): StripePaymentIntentsCollection
    {
        $params = array_merge(self::DEFAULT_PARAMS, $params);

        $hash = md5(serialize($params));

        $cashKey = 'stripe_fetch_payment_intents_' . $hash;

        /** @noinspection PhpUnhandledExceptionInspection */
        if ($this->cacheItemPool->hasItem($cashKey)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $collection = $this->cacheItemPool->getItem($cashKey)->get();

            assert($collection instanceof StripePaymentIntentsCollection);

            return $collection;
        }

        $collection = new StripePaymentIntentsCollection();

        /** @noinspection PhpUnhandledExceptionInspection */
        $result = $this->stripeClient->paymentIntents->all($params);

        foreach ($result->autoPagingIterator() as $item) {
            assert($item instanceof PaymentIntent);

            $collection->add($item);
        }

        $this->cacheItemPool->save(new SessionCacheItem(
            $cashKey,
            $collection,
        ));

        return $collection;
    }
}
