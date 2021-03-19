<?php

declare(strict_types=1);

use App\Globals;
use App\Http\AppMiddleware\CsrfInjectionMiddleware;
use App\Http\AppMiddleware\HoneyPotMiddleware;
use App\Http\AppMiddleware\MinifyMiddleware;
use App\Http\AppMiddleware\StaticCacheMiddleware;
use Config\General;
use Slim\App;
use Slim\Csrf\Guard as CsrfGuard;

return static function (App $app): void {
    $app->add(MinifyMiddleware::class);
    $app->add(HoneyPotMiddleware::class);
    $app->add(StaticCacheMiddleware::class);

    /** @psalm-suppress MixedMethodCall */
    if (Globals::di()->get(General::class)->devMode()) {
        return;
    }

    $app->add(CsrfGuard::class);
    $app->add(CsrfInjectionMiddleware::class);
};
