<?php

declare(strict_types=1);

namespace Config;

use App\Context\Queue\QueueRegisterEventListeners;
use App\Context\Users\UsersRegisterEventListeners;
use Psr\EventDispatcher\ListenerProviderInterface;

class RegisterEventListeners
{
    public function __construct(private ListenerProviderInterface $provider)
    {
    }

    public function register(): void
    {
        // Method names in subscriber classes must start with `on`. The event
        // will be derived from reflection to see what event it's subscribing to
        // $this->provider->addSubscriber(Test::class, Test::class);
        // public function onBeforeValidate(SaveUserBeforeValidate $beforeValidate) : void
        // {
        //     dd($beforeValidate);
        // }

        $provider = $this->provider;

        (new UsersRegisterEventListeners())->register($provider);
        (new QueueRegisterEventListeners())->register($provider);
    }
}
