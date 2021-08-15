<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Factories;

use App\Context\Content\Services\Internal\Contracts\ContentItemsFromDirectory;
use App\Context\Content\Services\Internal\Services\ContentItemsFromDirectoryCache;
use App\Context\Content\Services\Internal\Services\ContentItemsFromDirectoryFetchAndCache;

class ContentItemFromDirectoryFactory
{
    public function __construct(
        private ContentItemsFromDirectoryCache $cached,
        private ContentItemsFromDirectoryFetchAndCache $fetch,
    ) {
    }

    public function create(bool $hasCache): ContentItemsFromDirectory
    {
        if ($hasCache) {
            return $this->cached;
        }

        return $this->fetch;
    }
}
