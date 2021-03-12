<?php

declare(strict_types=1);

/**
 * @psalm-suppress MissingFile
 * @psalm-suppress MixedArgument
 */
return array_merge(
    require __DIR__ . '/Dependencies/CliQuestion.php',
    require __DIR__ . '/Dependencies/CookieApi.php',
    require __DIR__ . '/Dependencies/CsrfGuard.php',
    require __DIR__ . '/Dependencies/Email.php',
    require __DIR__ . '/Dependencies/EntityManager.php',
    require __DIR__ . '/Dependencies/Events.php',
    require __DIR__ . '/Dependencies/Filesystem.php',
    require __DIR__ . '/Dependencies/LoggerInterface.php',
    require __DIR__ . '/Dependencies/MailerSend.php',
    require __DIR__ . '/Dependencies/ResponseFactoryInterface.php',
    require __DIR__ . '/Dependencies/StaticCacheMiddleware.php',
    require __DIR__ . '/Dependencies/TwigEnvironment.php',
);
