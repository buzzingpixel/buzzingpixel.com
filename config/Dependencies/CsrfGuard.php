<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Csrf\Guard as CsrfGuard;
use Slim\Exception\HttpBadRequestException;

return [
    CsrfGuard::class => static function (ContainerInterface $di): CsrfGuard {
        $responseFactory = $di->get(ResponseFactoryInterface::class);

        assert($responseFactory instanceof ResponseFactoryInterface);

        $storage = null;

        return new CsrfGuard(
            $responseFactory,
            'csrf',
            $storage,
            static function (
                ServerRequestInterface $request
            ): void {
                throw new HttpBadRequestException(
                    $request,
                    'Invalid CSRF Token'
                );
            },
            200,
            16,
            true,
        );
    },
];
