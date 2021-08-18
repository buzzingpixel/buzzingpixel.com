<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Services;

use App\Context\Content\Contracts\ItemLinkResolverContract;
use App\Context\Content\Entities\ContentItem;
use App\Templating\TwigExtensions\SiteUrl;

class SupportArticleLinkResolver implements ItemLinkResolverContract
{
    public function __construct(private SiteUrl $siteUrl)
    {
    }

    public function resolveLinkToItem(ContentItem $contentItem): string
    {
        return $this->siteUrl->siteUrl(
            '/support/' . $contentItem->slug(),
        );
    }
}
