<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Services;

use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Content\Services\Internal\Contracts\ContentItemsFromDirectory;
use App\Context\Path\Entities\Path;
use Psr\Cache\CacheItemPoolInterface;

use function assert;

class ContentItemsFromDirectoryCache implements ContentItemsFromDirectory
{
    public function __construct(private CacheItemPoolInterface $cacheItemPool)
    {
    }

    /** @phpstan-ignore-next-line */
    public function get(Path $path, string $cacheKey): ContentItemCollection
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cache = $this->cacheItemPool->getItem($cacheKey);

        $collection = $cache->get();

        assert($collection instanceof ContentItemCollection);

        return $collection;
    }
}
