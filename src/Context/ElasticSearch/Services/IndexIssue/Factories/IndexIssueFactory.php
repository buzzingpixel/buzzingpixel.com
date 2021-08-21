<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexIssue\Factories;

use App\Context\ElasticSearch\Services\IndexIssue\Contracts\IndexIssueContract;
use App\Context\ElasticSearch\Services\IndexIssue\Services\IndexIssueCreate;
use App\Context\ElasticSearch\Services\IndexIssue\Services\IndexIssueExisting;
use App\Context\Issues\Entities\Issue;
use Elasticsearch\Client;
use Throwable;

class IndexIssueFactory
{
    public function __construct(
        private Client $client,
        // private IndexIssueNoOp $noOp,
        private IndexIssueCreate $create,
        private IndexIssueExisting $existing,
    ) {
    }

    public function get(Issue $issue): IndexIssueContract
    {
        // if (! $issue->isEnabled()) {
        //     return $this->noOp;
        // }

        try {
            $this->client->get([
                'index' => 'issues',
                'id' => $issue->id(),
            ]);

            return $this->existing;
        } catch (Throwable) {
            return $this->create;
        }
    }
}
