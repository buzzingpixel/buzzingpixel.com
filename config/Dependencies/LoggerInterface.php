<?php

declare(strict_types=1);

use Config\Logging\Logger;
use Monolog\Handler\RollbarHandler;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Rollbar\Rollbar;

return [
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
];
