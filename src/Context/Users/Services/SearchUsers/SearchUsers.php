<?php

declare(strict_types=1);

namespace App\Context\Users\Services\SearchUsers;

use App\Context\Users\Entities\SearchParams;
use App\Context\Users\Entities\UserResult;
use App\Context\Users\Services\SearchUsers\Factories\SearchUsersResultBuilderFactory;
use Elasticsearch\Client;

use function array_map;
use function assert;
use function is_array;

class SearchUsers
{
    public function __construct(
        private Client $client,
        private SearchUsersResultBuilderFactory $builderFactory,
    ) {
    }

    public function search(SearchParams $searchParams): UserResult
    {
        $esSearchResults = $this->client->search([
            'index' => 'users',
            'body' => [
                'size' => 10000,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'simple_query_string' => [
                                    'fields' => [
                                        'emailAddress',
                                        'displayName',
                                        'billingName',
                                        'billingCompany',
                                        'billingPhone',
                                        'billingCountryRegion',
                                        'billingCountryRegionName',
                                        'billingAddress',
                                        'billingAddressContinued',
                                        'billingCity',
                                        'billingStateProvince',
                                        'billingPostalCode',
                                    ],
                                    'query' => $searchParams->search(),
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

        return $this->builderFactory->make(resultIds: $resultIds)->buildResult(
            resultIds: $resultIds,
            searchParams: $searchParams,
        );
    }
}
