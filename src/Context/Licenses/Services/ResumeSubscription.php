<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Entities\License;
use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Stripe\QueueActions\SyncSubscriptionLicensesQueueAction;
use Stripe\StripeClient;
use Throwable;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

class ResumeSubscription
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private SyncSubscriptionLicensesQueueAction $syncSubscriptionLicenses,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function resume(License $license): void
    {
        try {
            $this->resumeInner($license);
        } catch (Throwable) {
        }
    }

    private function resumeInner(License $license): void
    {
        if ($license->isNotActive() || $license->isNotSubscription()) {
            return;
        }

        $subId = $license->stripeSubscriptionId();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->stripeClient->subscriptions->update(
            $subId,
            ['cancel_at_period_end' => false],
        );

        $this->syncSubscriptionLicenses->sync(
            context: ['stripeSubscriptionId' => $subId],
        );
    }
}
