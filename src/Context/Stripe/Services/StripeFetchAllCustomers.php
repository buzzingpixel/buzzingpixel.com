<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Entities\StripeCustomerCollection;
use App\Context\Stripe\Factories\StripeFactory;
use Stripe\Customer;
use Stripe\StripeClient;

use function assert;

class StripeFetchAllCustomers
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    /** @phpstan-ignore-next-line  */
    public function fetch(): StripeCustomerCollection
    {
        $collection = new StripeCustomerCollection();

        $customers = $this->stripeClient->customers
            ->all(['limit' => 999999]);

        foreach ($customers->autoPagingIterator() as $customer) {
            assert($customer instanceof Customer);

            $collection->add($customer);
        }

        return $collection;
    }
}
