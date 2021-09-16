<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexIssues\Services;

use Elasticsearch\Client;

use function array_walk;
use function in_array;

class DeleteIndexedIssuesNotPresentInIssues
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param string[] $issueIds
     * @param string[] $indexedIds
     */
    public function run(array $issueIds, array $indexedIds): void
    {
        array_walk(
            $indexedIds,
            function (string $indexedId) use ($issueIds): void {
                if (
                    in_array(
                        $indexedId,
                        $issueIds,
                        true
                    )
                ) {
                    return;
                }

                $this->client->delete([
                    'index' => 'issues',
                    'id' => $indexedId,
                ]);
            }
        );
    }
}
