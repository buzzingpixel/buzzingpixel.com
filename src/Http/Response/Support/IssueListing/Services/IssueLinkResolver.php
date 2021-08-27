<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueListing\Services;

use App\Context\Issues\Contracts\IssueLinkResolverContract;
use App\Context\Issues\Entities\Issue;
use App\Templating\TwigExtensions\SiteUrl;

class IssueLinkResolver implements IssueLinkResolverContract
{
    public function __construct(private SiteUrl $siteUrl)
    {
    }

    public function resolveLinkToIssue(Issue $issue): string
    {
        return $this->siteUrl->siteUrl(
            uri: '/support/issue/' . $issue->issueNumber(),
        );
    }
}
