<?php

declare(strict_types=1);

namespace App\Context\Content\Services;

use App\Context\Content\ContentApi;
use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Content\Services\Internal\Factories\ContentItemFromDirectoryFactory;
use App\Context\Path\Entities\Path;
use Psr\Cache\CacheItemPoolInterface;

class ContentItemsFromDirectory
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
        private ContentItemFromDirectoryFactory $factory,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function get(Path $path): ContentItemCollection
    {
        $cacheKey = ContentApi::cacheKeyWithPrefix(
            key: 'items.path.' . $path->getPath()
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $hasCache = $this->cacheItemPool->hasItem(key: $cacheKey);

        return $this->factory
            ->create(hasCache: $hasCache)
            ->get(path: $path, cacheKey: $cacheKey);
    }
}
