<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexUsers\Services;

use Elasticsearch\Client;

use function array_walk;
use function in_array;

class DeleteIndexedUsersNotPresentInUsers
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param string[] $userIds
     * @param string[] $indexedIds
     */
    public function run(array $userIds, array $indexedIds): void
    {
        array_walk(
            $indexedIds,
            function (string $indexedId) use ($userIds): void {
                if (
                    in_array(
                        $indexedId,
                        $userIds,
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
