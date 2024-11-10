<?php

declare(strict_types=1);

use App\Context\Content\Services\Internal\Factories\ContentItemFromDirectoryFactory;
use App\Context\Content\Services\Internal\Services\ContentItemsFromDirectoryFetchAndCache;

use function DI\autowire;

return [
    ContentItemFromDirectoryFactory::class => autowire(ContentItemFromDirectoryFactory::class)
        ->constructorParameter(
            'contentCacheEnabled',
            (bool) getenv('CONTENT_CACHE_ENABLED'),
        ),
    ContentItemsFromDirectoryFetchAndCache::class => autowire(ContentItemsFromDirectoryFetchAndCache::class)
        ->constructorParameter(
            'contentCacheEnabled',
            (bool) getenv('CONTENT_CACHE_ENABLED'),
        ),
];
