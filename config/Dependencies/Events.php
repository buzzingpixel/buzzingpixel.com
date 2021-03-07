<?php

declare(strict_types=1);

use Crell\Tukio\Dispatcher;
use Crell\Tukio\OrderedListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

use function DI\autowire;
use function DI\get;

return [
    EventDispatcherInterface::class => autowire(Dispatcher::class)->constructorParameter(
        'logger',
        get(LoggerInterface::class),
    ),
    ListenerProviderInterface::class => get(OrderedListenerProvider::class),
    OrderedListenerProvider::class => static function (ContainerInterface $di): OrderedListenerProvider {
        return new OrderedListenerProvider($di);
    },
];
