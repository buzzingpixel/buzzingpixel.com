<?php

declare(strict_types=1);

namespace App\Context\Stripe\QueueActions;

use App\Context\Stripe\Factories\SyncCustomerFactory;
use App\Context\Stripe\Services\StripeFetchCustomers;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Stripe\Customer;

use function array_key_exists;
use function assert;

class SyncUserWithStripeQueueAction
{
    public function __construct(
        private StripeFetchCustomers $stripeFetchCustomers,
        private UserApi $userApi,
        private SyncCustomerFactory $syncCustomerFactory,
    ) {
    }

    /**
     * @param array<string, string> $context
     */
    public function sync(array $context): void
    {
        assert(array_key_exists('userId', $context));

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withId($context['userId']),
        );

        assert($user instanceof User);

        $customer = $this->stripeFetchCustomers->fetch()
            ->filter(
                static fn (Customer $c) => $c->email === $user->emailAddress(),
            )
        ->firstOrNull();

        $this->syncCustomerFactory
            ->createSyncCustomer(
                customer: $customer,
                user: $user,
            )
            ->sync();
    }
}
