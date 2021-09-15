<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexAllIssues;

use App\Context\ElasticSearch\Services\IndexAllIssues\Services\DeleteIndexedIssuesNotPresentInIssues;
use App\Context\ElasticSearch\Services\IndexIssue\IndexIssue;
use App\Context\Issues\Entities\Issue;
use App\Context\Issues\IssuesApi;
use App\Persistence\QueryBuilders\Issues\IssueQueryBuilder;
use Elasticsearch\Client;

use function array_map;

class IndexAllIssues
{
    public function __construct(
        private Client $client,
        private IssuesApi $issuesApi,
        private IndexIssue $indexIssue,
        private DeleteIndexedIssuesNotPresentInIssues $deleteIndexedIssuesNotPresentInIssues,
    ) {
    }

    public function indexAllIssues(): void
    {
        $issues = $this->issuesApi->fetchIssues(
            queryBuilder: (new IssueQueryBuilder())
                ->withIsEnabled(),
        );

        $issueIds = $issues->mapToArray(
            static fn (Issue $i) => $i->id(),
        );

        $index = $this->client->search([
            'index' => 'issues',
            'body' => ['size' => 10000],
        ]);

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         * @psalm-suppress MissingClosureReturnType
         */
        $indexedIds = array_map(
            static fn (array $i) => $i['_id'],
            $index['hits']['hits'],
        );

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        $this->deleteIndexedIssuesNotPresentInIssues->run(
            issueIds: $issueIds,
            indexedIds: $indexedIds,
        );

        $issues->map(
            function (Issue $issue): void {
                $this->indexIssue->indexIssue($issue);
            }
        );
    }
}
