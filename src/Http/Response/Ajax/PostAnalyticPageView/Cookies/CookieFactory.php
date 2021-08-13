<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\PostAnalyticPageView\Cookies;

use App\Persistence\UuidFactoryWithOrderedTimeCodec;
use buzzingpixel\cookieapi\interfaces\CookieApiInterface;
use buzzingpixel\cookieapi\interfaces\CookieInterface;

class CookieFactory
{
    public function __construct(
        private CookieApiInterface $cookieApi,
        private UuidFactoryWithOrderedTimeCodec $uuidFactory,
    ) {
    }

    public function create(): CookieInterface
    {
        $cookie = $this->cookieApi->retrieveCookie('activity_id');

        if ($cookie !== null) {
            return $cookie;
        }

        $cookie = $this->cookieApi->makeCookie(
            'activity_id',
            $this->uuidFactory->uuid1()->toString(),
        );

        $this->cookieApi->saveCookie($cookie);

        return $cookie;
    }
}
