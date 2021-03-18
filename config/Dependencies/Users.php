<?php

declare(strict_types=1);

use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use Psr\Container\ContainerInterface;

return [
    LoggedInUser::class => static function (ContainerInterface $di): LoggedInUser {
        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedMethodCall
         */
        return new LoggedInUser(
            $di->get(UserApi::class)->fetchLoggedInUser(),
        );
    },
];
