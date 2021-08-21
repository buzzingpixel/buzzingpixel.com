<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch;

use App\Context\ElasticSearch\Services\IndexAllIssues\IndexAllIssues;
use App\Context\ElasticSearch\Services\IndexIssue\IndexIssue;
use App\Context\ElasticSearch\Services\SetUpIndices;
use App\Context\Issues\Entities\Issue;

class ElasticSearchApi
{
    public function __construct(
        private IndexIssue $indexIssue,
        private SetUpIndices $setUpIndices,
        private IndexAllIssues $indexAllIssues,
    ) {
    }

    public function setUpIndices(): void
    {
        $this->setUpIndices->setUp();
    }

    public function indexIssue(Issue $issue): void
    {
        $this->indexIssue->indexIssue($issue);
    }

    public function indexAllIssues(): void
    {
        $this->indexAllIssues->indexAllIssues();
    }
}
