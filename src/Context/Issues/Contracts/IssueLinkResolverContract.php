<?php

declare(strict_types=1);

namespace App\Context\Issues\Contracts;

use App\Context\Issues\Entities\Issue;

interface IssueLinkResolverContract
{
    public function resolveLinkToIssue(Issue $issue): string;
}
