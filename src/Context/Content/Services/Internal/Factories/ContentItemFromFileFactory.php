<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Factories;

use App\Context\Content\Services\Internal\Contracts\ContentItemFromFilePath;
use App\Context\Content\Services\Internal\Services\ContentItemFromFilePathCache;
use App\Context\Content\Services\Internal\Services\ContentItemFromFilePathFetchAndCache;

class ContentItemFromFileFactory
{
    public function __construct(
        private ContentItemFromFilePathCache $cached,
        private ContentItemFromFilePathFetchAndCache $fetch,
    ) {
    }

    public function create(bool $hasCache): ContentItemFromFilePath
    {
        if ($hasCache) {
            return $this->cached;
        }

        return $this->fetch;
    }
}
