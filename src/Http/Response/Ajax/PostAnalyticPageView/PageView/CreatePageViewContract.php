<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\PostAnalyticPageView\PageView;

use App\Context\Users\Entities\User;
use buzzingpixel\cookieapi\interfaces\CookieInterface;

interface CreatePageViewContract
{
    public function create(
        CookieInterface $cookie,
        ?User $user = null,
        string $uri = ''
    ): void;
}
