<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Licenses\LicenseApi;
use App\Context\Stripe\Factories\SyncSubscriptionItemFactory;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Stripe\Subscription;
use Stripe\SubscriptionItem;

use function assert;

class SyncLicenses
{
    public function __construct(
        private LicenseApi $licenseApi,
        private SyncSubscriptionItemFactory $syncSubscriptionItemFactory,
        private StripeFetchSubscriptionItems $stripeFetchSubscriptionItems
    ) {
    }

    public function sync(): void
    {
        // Get all subscription based licenses
        $licenses = $this->licenseApi->fetchLicenses(
            queryBuilder: (new LicenseQueryBuilder())
                ->withStripeId(
                    value: '',
                    comparison: '!=',
                )
                ->withExpiresAtNotNull(),
        );

        $subscriptions = $this->stripeFetchSubscriptionItems->fetch();

        $subscriptions->mapToArray(
            function (Subscription $subscription) use (
                $licenses,
            ): void {
                foreach ($subscription->items->autoPagingIterator() as $item) {
                    assert($item instanceof SubscriptionItem);

                    $license = $licenses->where(
                        'stripeId',
                        $item->id
                    )->firstOrNull();

                    $this->syncSubscriptionItemFactory
                        ->createSyncSubscriptionItem(
                            subscriptionItem: $item,
                            subscription: $subscription,
                            license: $license
                        )
                        ->sync(
                            subscriptionItem: $item,
                            subscription: $subscription,
                            license: $license,
                        );
                }
            }
        );
    }
}
