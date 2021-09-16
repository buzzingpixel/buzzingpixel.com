<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues;

use App\Context\Issues\Entities\FetchParams;
use App\Context\Issues\Entities\IssuesResult;
use App\Context\Issues\Services\SearchIssues\Factories\SearchIssueBuilderFactory;
use App\Context\Users\Entities\User;
use Elasticsearch\Client;

use function array_map;
use function assert;
use function is_array;

class SearchPublicPlusUsersIssues
{
    public function __construct(
        private Client $client,
        private SearchIssueBuilderFactory $searchIssueBuilderFactory,
    ) {
    }

    public function search(
        string $searchString,
        User $user,
        ?FetchParams $fetchParams = null,
    ): IssuesResult {
        $body = ['size' => 10000];

        if ($searchString !== '') {
            $body['query'] = [
                'bool' => [
                    'must' => [
                        [
                            'simple_query_string' => [
                                'fields' => [
                                    'solution',
                                    'messagesText',
                                    'softwareName',
                                    'shortDescription',
                                    'additionalEnvDetails',
                                ],
                                'query' => $searchString,
                            ],
                        ],
                    ],
                ],
            ];
        }

        $esSearchResults = $this->client->search([
            'index' => 'issues',
            'body' => $body,
        ]);

        /** @psalm-suppress MixedArrayAccess */
        $results = $esSearchResults['hits']['hits'];

        assert(is_array($results));

        /**
         * @var string[] $resultIds
         * @psalm-suppress MissingClosureReturnType
         */
        $resultIds = array_map(
            static fn (array $i) => $i['_id'],
            $results,
        );

        return $this->searchIssueBuilderFactory
            ->getSearchIssueBuilder(
                resultIds: $resultIds,
                mode: 'publicPlusUser',
            )
            ->buildResult(
                resultIds: $resultIds,
                fetchParams: $fetchParams ?? new FetchParams(),
                user: $user,
            );
    }
}
