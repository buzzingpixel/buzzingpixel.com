<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrder\Services;

use App\Context\ElasticSearch\Services\IndexOrder\Contracts\IndexOrderContract;
use App\Context\Orders\Entities\Order;
use Elasticsearch\Client;

class IndexOrderCreate implements IndexOrderContract
{
    public function __construct(private Client $client)
    {
    }

    public function indexOrder(Order $order): void
    {
        $this->client->index([
            'index' => 'orders',
            'id' => $order->id(),
            'body' => $order->getIndexArray(),
        ]);
    }
}
