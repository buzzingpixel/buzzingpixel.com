<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Contracts;

use App\Context\Content\Entities\ContentItem;
use App\Context\Path\Entities\Path;

interface ContentItemFromFilePath
{
    public function get(Path $path, string $cacheKey): ContentItem;
}
