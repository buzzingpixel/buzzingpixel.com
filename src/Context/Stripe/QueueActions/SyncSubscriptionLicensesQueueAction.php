<?php

declare(strict_types=1);

namespace App\Context\Stripe\QueueActions;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Services\FetchLicenses;
use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Stripe\Factories\SyncSubscriptionItemFactory;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Stripe\StripeClient;

use function array_key_exists;
use function assert;

class SyncSubscriptionLicensesQueueAction
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private FetchLicenses $fetchLicenses,
        private SyncSubscriptionItemFactory $syncSubscriptionItemFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    /**
     * @param array<string, string> $context
     */
    public function sync(array $context): void
    {
        assert(array_key_exists(
            'stripeSubscriptionId',
            $context,
        ));

        /** @noinspection PhpUnhandledExceptionInspection */
        $licenses = $this->fetchLicenses->fetch(
            (new LicenseQueryBuilder())
                ->withStripeSubscriptionId(
                    $context['stripeSubscriptionId']
                ),
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $subscription = $this->stripeClient->subscriptions->retrieve(
            $context['stripeSubscriptionId'],
        );

        $licenses->map(
            function (License $license) use ($subscription): void {
                $client = $this->stripeClient;

                $item = $client->subscriptionItems->retrieve(
                    $license->stripeSubscriptionItemId(),
                );

                $this->syncSubscriptionItemFactory
                    ->createSyncSubscriptionItem(
                        subscriptionItem: $item,
                        subscription: $subscription,
                        license: $license,
                    )
                    ->sync(
                        subscriptionItem: $item,
                        subscription: $subscription,
                        license: $license,
                    );
            }
        );
    }
}
