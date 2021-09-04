<?php

declare(strict_types=1);

namespace App\Context\Content\Services;

use App\Context\Content\ContentApi;
use App\Context\Content\Entities\ContentItem;
use App\Context\Content\Services\Internal\Factories\ContentItemFromFileFactory;
use App\Context\Path\Entities\Path;
use Psr\Cache\CacheItemPoolInterface;

class ContentItemFromFilePath
{
    public function __construct(
        private CacheItemPoolInterface $cache,
        private ContentItemFromFileFactory $factory,
    ) {
    }

    public function get(Path $path): ContentItem
    {
        $cacheKey = ContentApi::cacheKeyWithPrefix(
            key: 'singleItems.path.' . $path->getPath(),
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $hasCache = $this->cache->hasItem(key: $cacheKey);

        return $this->factory
            ->create(hasCache: $hasCache)
            ->get(path: $path, cacheKey: $cacheKey);
    }
}
