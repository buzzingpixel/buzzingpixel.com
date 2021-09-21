<?php

declare(strict_types=1);

namespace App\Context\Orders\Services\SearchOrders;

use App\Context\Orders\Entities\OrderResult;
use App\Context\Orders\Entities\SearchParams;
use App\Context\Orders\Services\SearchOrders\Factories\SearchOrderBuilderFactory;
use Elasticsearch\Client;

use function array_map;
use function assert;
use function is_array;

class SearchOrders
{
    public function __construct(
        private Client $client,
        private SearchOrderBuilderFactory $builderFactory
    ) {
    }

    public function search(SearchParams $searchParams): OrderResult
    {
        $esSearchResults = $this->client->search([
            'index' => 'orders',
            'body' => [
                'size' => 10000,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'simple_query_string' => [
                                    'fields' => [
                                        'oldOrderNumber',
                                        'subTotal',
                                        'tax',
                                        'total',
                                        'billingName',
                                        'billingCompany',
                                        'billingPhone',
                                        'billingCountryRegion',
                                        'billingAddress',
                                        'billingAddressContinued',
                                        'billingCity',
                                        'billingStateProvince',
                                        'billingPostalCode',
                                        'userEmailAddress',
                                        'userDisplayName',
                                        'softwareNames',
                                        'softwareSlugs',
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
