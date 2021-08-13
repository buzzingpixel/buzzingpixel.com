<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use buzzingpixel\cookieapi\interfaces\CookieInterface;

trait Cookie
{
    private CookieInterface $cookie;

    public function cookie(): CookieInterface
    {
        return $this->cookie;
    }

    /**
     * @return $this
     */
    public function withCookie(CookieInterface $cookie): self
    {
        $clone = clone $this;

        $clone->cookie = $cookie;

        return $clone;
    }
}
