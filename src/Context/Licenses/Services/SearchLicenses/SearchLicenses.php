<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\SearchLicenses;

use App\Context\Licenses\Entities\LicenseResult;
use App\Context\Licenses\Entities\SearchParams;
use App\Context\Licenses\Services\SearchLicenses\Factories\SearchLicensesResultBuilderFactory;
use Elasticsearch\Client;

use function array_map;
use function assert;
use function is_array;

class SearchLicenses
{
    public function __construct(
        private Client $client,
        private SearchLicensesResultBuilderFactory $builderFactory
    ) {
    }

    public function search(SearchParams $searchParams): LicenseResult
    {
        $esSearchResults = $this->client->search([
            'index' => 'licenses',
            'body' => [
                'size' => 10000,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'simple_query_string' => [
                                    'fields' => [
                                        'licenseKey',
                                        'userNotes',
                                        'adminNotes',
                                        'authorizedDomains',
                                        'userId',
                                        'userEmailAddress',
                                        'userDisplayName',
                                        'software',
                                        'softwareSlug',
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
