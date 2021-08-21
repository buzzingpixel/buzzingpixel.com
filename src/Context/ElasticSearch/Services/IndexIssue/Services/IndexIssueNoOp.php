<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexIssue\Services;

use App\Context\ElasticSearch\Services\IndexIssue\Contracts\IndexIssueContract;
use App\Context\Issues\Entities\Issue;

class IndexIssueNoOp implements IndexIssueContract
{
    public function indexIssue(Issue $issue): void
    {
    }
}
