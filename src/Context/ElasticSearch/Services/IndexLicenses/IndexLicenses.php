<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexLicenses;

use App\Context\ElasticSearch\Services\IndexLicense\IndexLicense;
use App\Context\ElasticSearch\Services\IndexLicenses\Services\DeleteIndexedLicensesNotPresentInLicenses;
use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Elasticsearch\Client;

use function array_map;

class IndexLicenses
{
    public function __construct(
        private Client $client,
        private LicenseApi $licenseApi,
        private IndexLicense $indexLicense,
        private DeleteIndexedLicensesNotPresentInLicenses $deleteLicensesNotPresent,
    ) {
    }

    public function indexLicenses(): void
    {
        $licenses = $this->licenseApi->fetchLicenses(
            queryBuilder: new LicenseQueryBuilder(),
        );

        $licenseIds = $licenses->mapToArray(
            static fn (License $l) => $l->id(),
        );

        $index = $this->client->search([
            'index' => 'licenses',
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
        $this->deleteLicensesNotPresent->run(
            licenseIds: $licenseIds,
            indexedIds: $indexedIds,
        );

        $licenses->map(function (License $license): void {
            $this->indexLicense->indexLicense(license: $license);
        });
    }
}
