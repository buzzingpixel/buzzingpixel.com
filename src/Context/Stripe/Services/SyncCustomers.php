<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Stripe\Factories\SyncCustomerFactory;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Stripe\Customer;
use Stripe\StripeClient;

use function array_map;
use function in_array;

class SyncCustomers
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private UserApi $userApi,
        private SyncCustomerFactory $syncCustomerFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function sync(): void
    {
        // Get all of our local users
        $users = $this->userApi->fetchUsers(
            new UserQueryBuilder()
        );

        /** @var Customer[] $customers */
        $customers = $this->stripeClient->customers->all()->data;

        // Sync all customers that already exist on Stripe, save the ids of the
        // updated items
        $updatedUsersIds = array_map(
            function (Customer $customer) use ($users): ?string {
                $user = $users->where(
                    'id',
                    /** @phpstan-ignore-next-line  */
                    $customer->metadata->id,
                )->firstOrNull();

                if ($user === null) {
                    return null;
                }

                $this->syncCustomerFactory
                    ->createSyncCustomer(
                        customer: $customer,
                        user: $user,
                    )
                    ->sync();

                return $user->id();
            },
            $customers
        );

        // Add new customers to Stripe
        $users->map(function (User $user) use (
            $updatedUsersIds
        ): void {
            if (
                in_array(
                    $user->id(),
                    $updatedUsersIds,
                    true
                )
            ) {
                return;
            }

            $this->syncCustomerFactory->createSyncCustomer(user: $user)->sync();
        });
    }
}
