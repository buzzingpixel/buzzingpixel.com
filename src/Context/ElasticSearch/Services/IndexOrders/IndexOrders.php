<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrders;

use App\Context\ElasticSearch\Services\IndexOrder\IndexOrder;
use App\Context\ElasticSearch\Services\IndexOrders\Services\DeleteIndexedOrdersNotPresentInOrders;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\OrderApi;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Elasticsearch\Client;

use function array_map;

class IndexOrders
{
    public function __construct(
        private Client $client,
        private OrderApi $orderApi,
        private IndexOrder $indexOrder,
        private DeleteIndexedOrdersNotPresentInOrders $deleteOrdersNotPresent,
    ) {
    }

    public function indexOrders(): void
    {
        $orders = $this->orderApi->fetchOrders(
            queryBuilder: new OrderQueryBuilder(),
        );

        $orderIds = $orders->mapToArray(
            static fn (Order $o) => $o->id(),
        );

        $index = $this->client->search([
            'index' => 'orders',
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
        $this->deleteOrdersNotPresent->run(
            orderIds: $orderIds,
            indexedIds: $indexedIds,
        );

        $orders->map(function (Order $order): void {
            $this->indexOrder->indexOrder(order: $order);
        });
    }
}
