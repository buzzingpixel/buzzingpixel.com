<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexIssue\Contracts;

use App\Context\Issues\Entities\Issue;

interface IndexIssueContract
{
    public function indexIssue(Issue $issue): void;
}
