<?php

declare(strict_types=1);

use buzzingpixel\cookieapi\CookieApi;
use buzzingpixel\cookieapi\interfaces\CookieApiInterface;
use buzzingpixel\cookieapi\PhpFunctions;

use function DI\get;

return [
    CookieApi::class => static function (): CookieApi {
        /** @phpstan-ignore-next-line */
        $encryptionKey = (string) getenv('ENCRYPTION_KEY');

        /**
         * @psalm-suppress NullReference
         */
        return new CookieApi(
            $_COOKIE,
            $encryptionKey,
            new PhpFunctions()
        );
    },
    CookieApiInterface::class => get(CookieApi::class),
];
