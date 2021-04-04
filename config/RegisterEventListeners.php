<?php

declare(strict_types=1);

namespace Config;

use App\Context\Queue\QueueRegisterEventListeners;
use App\Context\Software\SoftwareRegisterEventListeners;
use App\Context\Users\UsersRegisterEventListeners;
use Crell\Tukio\OrderedListenerProvider;

class RegisterEventListeners
{
    public function __construct(private OrderedListenerProvider $provider)
    {
    }

    public function register(): void
    {
        // Method names in subscriber classes must start with `on`. The event
        // will be derived from reflection to see what event it's subscribing to

        $provider = $this->provider;
        SoftwareRegisterEventListeners::register($provider);
        UsersRegisterEventListeners::register($provider);
        QueueRegisterEventListeners::register($provider);
    }
}
