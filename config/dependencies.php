<?php

declare(strict_types=1);

use App\Http\AppMiddleware\StaticCacheMiddleware;
use Config\Factories\TwigEnvironmentFactory;
use Config\Logging\Logger;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Monolog\Handler\RollbarHandler;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Rollbar\Rollbar;
use Slim\Csrf\Guard as CsrfGuard;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Factory\ResponseFactory;
use Twig\Environment as TwigEnvironment;

use function DI\autowire;
use function DI\get;

return [
    CsrfGuard::class => static function (ContainerInterface $di): CsrfGuard {
        $responseFactory = $di->get(ResponseFactoryInterface::class);

        assert($responseFactory instanceof ResponseFactoryInterface);

        $guard = new CsrfGuard($responseFactory);

        $guard->setFailureHandler(
            static function (
                ServerRequestInterface $request
            ): void {
                throw new HttpBadRequestException(
                    $request,
                    'Invalid CSRF Token'
                );
            }
        );

        return $guard;
    },
    Filesystem::class => autowire(Filesystem::class)
        ->constructorParameter(
            'adapter',
            get(LocalFilesystemAdapter::class),
        ),
    LocalFilesystemAdapter::class => autowire(LocalFilesystemAdapter::class)
        ->constructorParameter(
            'location',
            '/',
        ),
    LoggerInterface::class => static function (): LoggerInterface {
        /** @phpstan-ignore-next-line */
        $logLevel = getenv('LOG_LEVEL') ?: 'DEBUG';

        assert(is_string($logLevel));

        $logger = new Logger('app');

        $logPath = getenv('LOG_FILE');

        if ($logPath !== false) {
            assert(is_string($logPath));

            /** @psalm-suppress MixedArgument */
            $logger->pushHandler(
                new StreamHandler(
                    $logPath,
                    constant(Logger::class . '::' . $logLevel),
                ),
            );
        }

        $rollBarAccessToken = getenv('ROLLBAR_ACCESS_TOKEN');

        if ($rollBarAccessToken !== false && $rollBarAccessToken !== '') {
            Rollbar::init(
                [
                    'access_token' => $rollBarAccessToken,
                    /** @phpstan-ignore-next-line */
                    'environment' => getenv('ROLLBAR_ENVIRONMENT') ?:
                        'dev',
                ]
            );

            /** @psalm-suppress MixedArgument */
            $logger->pushHandler(
                new RollbarHandler(Rollbar::logger())
            );
        }

        return $logger;
    },
    ResponseFactoryInterface::class => autowire(ResponseFactory::class),
    StaticCacheMiddleware::class => autowire(StaticCacheMiddleware::class)
        ->constructorParameter(
            'staticCacheEnabled',
            (bool) getenv('STATIC_CACHE_ENABLED'),
        ),
    TwigEnvironment::class => static function (ContainerInterface $di): TwigEnvironment {
        return (new TwigEnvironmentFactory())($di);
    },
];
