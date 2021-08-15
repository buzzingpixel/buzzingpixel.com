<?php

declare(strict_types=1);

namespace App\Context\Content;

use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Content\Services\ContentItemsFromDirectory;
use App\Context\Path\Entities\Path;

class ContentApi
{
    public const CACHE_KEY_PREFIX = 'content_api.';

    public function __construct(
        private ContentItemsFromDirectory $contentItemsFromDirectory,
    ) {
    }

    public static function cacheKeyWithPrefix(string $key): string
    {
        return self::CACHE_KEY_PREFIX . $key;
    }

    /** @phpstan-ignore-next-line */
    public function contentItemsFromDirectory(Path $path): ContentItemCollection
    {
        return $this->contentItemsFromDirectory->get(path: $path);
    }
}
