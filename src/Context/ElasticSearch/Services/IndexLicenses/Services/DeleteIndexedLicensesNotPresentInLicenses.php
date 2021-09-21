<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexLicenses\Services;

use Elasticsearch\Client;

use function array_walk;
use function in_array;

class DeleteIndexedLicensesNotPresentInLicenses
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param string[] $licenseIds
     * @param string[] $indexedIds
     */
    public function run(array $licenseIds, array $indexedIds): void
    {
        array_walk(
            $indexedIds,
            function (string $indexedId) use ($licenseIds): void {
                if (
                    in_array(
                        $indexedId,
                        $licenseIds,
                        true
                    )
                ) {
                    return;
                }

                $this->client->delete([
                    'index' => 'licenses',
                    'id' => $indexedId,
                ]);
            }
        );
    }
}
