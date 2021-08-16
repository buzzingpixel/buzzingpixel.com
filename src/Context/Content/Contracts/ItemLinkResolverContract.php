<?php

declare(strict_types=1);

namespace App\Context\Content\Contracts;

use App\Context\Content\Entities\ContentItem;

interface ItemLinkResolverContract
{
    public function resolveLinkToItem(ContentItem $contentItem): string;
}
