<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\UpdateMaxVersionOnLicenses;

class UpdateMaxVersionOnLicenses
{
    public function __construct(
        private UpdateMaxVersionOnSubscriptionLicenses $subscriptions,
        private UpdateMaxVersionOnNonSubscriptionLicenses $nonSubscriptions,
    ) {
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function update(): void
    {
        $this->subscriptions->update();
        $this->nonSubscriptions->update();
    }
}
