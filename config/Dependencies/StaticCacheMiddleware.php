<?php

declare(strict_types=1);

use App\Http\AppMiddleware\StaticCacheMiddleware;

use function DI\autowire;

return [
    StaticCacheMiddleware::class => autowire(StaticCacheMiddleware::class)
        ->constructorParameter(
            'staticCacheEnabled',
            (bool) getenv('STATIC_CACHE_ENABLED'),
        ),
];
