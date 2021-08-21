<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexIssue\Services;

use App\Context\ElasticSearch\Services\IndexIssue\Contracts\IndexIssueContract;
use App\Context\Issues\Entities\Issue;
use Elasticsearch\Client;

class IndexIssueCreate implements IndexIssueContract
{
    public function __construct(private Client $client)
    {
    }

    public function indexIssue(Issue $issue): void
    {
        $this->client->index([
            'index' => 'issues',
            'id' => $issue->id(),
            'body' => $issue->getIndexArray(),
        ]);
    }
}
