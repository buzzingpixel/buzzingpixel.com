<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexIssue;

use App\Context\ElasticSearch\Services\IndexIssue\Factories\IndexIssueFactory;
use App\Context\Issues\Entities\Issue;

class IndexIssue
{
    public function __construct(private IndexIssueFactory $indexIssueFactory)
    {
    }

    public function indexIssue(Issue $issue): void
    {
        $this->indexIssueFactory->get(issue: $issue)->indexIssue(issue: $issue);
    }
}
