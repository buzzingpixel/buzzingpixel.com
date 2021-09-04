<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Services;

use App\Context\Content\Entities\ContentItem;
use App\Context\Content\Services\Internal\Contracts\ContentItemFromFilePath;
use App\Context\Path\Entities\Path;
use Psr\Cache\CacheItemPoolInterface;

use function assert;

class ContentItemFromFilePathCache implements ContentItemFromFilePath
{
    public function __construct(private CacheItemPoolInterface $cacheItemPool)
    {
    }

    public function get(Path $path, string $cacheKey): ContentItem
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $cache = $this->cacheItemPool->getItem($cacheKey);

        $contentItem = $cache->get();

        assert($contentItem instanceof ContentItem);

        return $contentItem;
    }
}
