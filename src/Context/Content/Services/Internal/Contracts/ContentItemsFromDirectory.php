<?php

declare(strict_types=1);

namespace App\Context\Content\Services\Internal\Contracts;

use App\Context\Content\Entities\ContentItemCollection;
use App\Context\Path\Entities\Path;

interface ContentItemsFromDirectory
{
    /** @phpstan-ignore-next-line */
    public function get(Path $path, string $cacheKey): ContentItemCollection;
}
