<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SearchIssues;

use App\Context\Issues\Entities\IssueCollection;
use App\Context\Issues\Services\SearchIssues\Factories\SearchIssueBuilderFactory;
use Elasticsearch\Client;

use function array_map;
use function assert;
use function is_array;

class SearchPublicIssues
{
    public function __construct(
        private Client $client,
        private SearchIssueBuilderFactory $searchIssueBuilderFactory,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function search(string $searchString): IssueCollection
    {
        $esSearchResults = $this->client->search([
            'index' => 'issues',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => ['isPublic' => true],
                            ],
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
                ],
            ],
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
            ->getSearchIssueBuilder(resultIds: $resultIds)
            ->buildResult(resultIds: $resultIds);
    }
}
