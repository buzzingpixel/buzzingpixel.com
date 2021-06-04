<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Stripe\Factories\SyncCustomerFactory;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Stripe\Customer;

use function in_array;

class SyncCustomers
{
    public function __construct(
        private StripeFetchCustomers $stripeFetchCustomers,
        private UserApi $userApi,
        private SyncCustomerFactory $syncCustomerFactory,
    ) {
    }

    public function sync(): void
    {
        // Get all of our local users
        $users = $this->userApi->fetchUsers(
            new UserQueryBuilder()
        );

        $customers = $this->stripeFetchCustomers->fetch();

        // Sync all customers that already exist on Stripe, save the ids of the
        // updated items
        $updatedUsersIds = $customers->mapToArray(
            function (Customer $customer) use ($users): ?string {
                $metaData = $customer->metadata->toArray();

                $user = $users->where(
                    'id',
                    $metaData['id'] ?? null,
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
            }
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
