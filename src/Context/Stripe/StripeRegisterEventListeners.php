<?php

declare(strict_types=1);

namespace App\Context\Stripe;

use App\Context\Stripe\EventListeners\SaveUserOnAfterSaveSyncWithStripe;
use Crell\Tukio\OrderedListenerProvider;

class StripeRegisterEventListeners
{
    public static function register(OrderedListenerProvider $provider): void
    {
        $provider->addSubscriber(
            SaveUserOnAfterSaveSyncWithStripe::class,
            SaveUserOnAfterSaveSyncWithStripe::class,
        );
    }
}
