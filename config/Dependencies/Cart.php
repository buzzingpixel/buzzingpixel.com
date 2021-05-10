<?php

declare(strict_types=1);

use App\Context\Cart\Entities\CurrentUserCart;
use App\Context\Cart\Services\FetchCurrentUserCart;
use Psr\Container\ContainerInterface;

return [
    CurrentUserCart::class => static function (ContainerInterface $di): CurrentUserCart {
        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedMethodCall
         */
        return new CurrentUserCart(
            $di->get(FetchCurrentUserCart::class)->fetch(),
        );
    },
];
