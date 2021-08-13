<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\PostAnalyticPageView\PageView;

use App\Context\Analytics\AnalyticsApi;
use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Context\Users\Entities\User;
use buzzingpixel\cookieapi\interfaces\CookieInterface;

class CreatePageView implements CreatePageViewContract
{
    public function __construct(private AnalyticsApi $analyticsApi)
    {
    }

    public function create(
        CookieInterface $cookie,
        ?User $user = null,
        string $uri = ''
    ): void {
        $this->analyticsApi->saveAnalytic(analytic: new AnalyticsEntity(
            cookie: $cookie,
            user: $user,
            loggedInOnPageLoad: $user !== null,
            uri: $uri,
        ));
    }
}
