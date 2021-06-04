<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Entities\StripeCustomerCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\Customer;
use Stripe\StripeClient;

use function array_merge;
use function assert;

class StripeFetchCustomers
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
    public function fetch(array $params = []): StripeCustomerCollection
    {
        $collection = new StripeCustomerCollection();

        $result = $this->stripeClient->customers
            ->all(array_merge(self::DEFAULT_PARAMS, $params));

        foreach ($result->autoPagingIterator() as $item) {
            assert($item instanceof Customer);

            $collection->add($item);
        }

        return $collection;
    }
}
