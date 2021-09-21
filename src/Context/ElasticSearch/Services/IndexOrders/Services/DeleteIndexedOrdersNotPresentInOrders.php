<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrders\Services;

use Elasticsearch\Client;

use function array_walk;
use function in_array;

class DeleteIndexedOrdersNotPresentInOrders
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param string[] $orderIds
     * @param string[] $indexedIds
     */
    public function run(array $orderIds, array $indexedIds): void
    {
        array_walk(
            $indexedIds,
            function (string $indexedId) use ($orderIds): void {
                if (
                    in_array(
                        $indexedId,
                        $orderIds,
                        true
                    )
                ) {
                    return;
                }

                $this->client->delete([
                    'index' => 'orders',
                    'id' => $indexedId,
                ]);
            }
        );
    }
}
