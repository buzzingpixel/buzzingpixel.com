<?php

declare(strict_types=1);

namespace App\Http\Response\News;

use App\Context\Content\Contracts\ItemLinkResolverContract;
use App\Context\Content\Entities\ContentItem;
use App\Templating\TwigExtensions\SiteUrl;

class ItemLinkResolver implements ItemLinkResolverContract
{
    public function __construct(private SiteUrl $siteUrl)
    {
    }

    public function resolveLinkToItem(ContentItem $contentItem): string
    {
        return $this->siteUrl->siteUrl(
            uri: '/news/' . $contentItem->slug(),
        );
    }
}
