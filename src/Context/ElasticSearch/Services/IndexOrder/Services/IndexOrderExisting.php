<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrder\Services;

use App\Context\ElasticSearch\Services\IndexOrder\Contracts\IndexOrderContract;
use App\Context\Orders\Entities\Order;
use Elasticsearch\Client;

class IndexOrderExisting implements IndexOrderContract
{
    public function __construct(private Client $client)
    {
    }

    public function indexOrder(Order $order): void
    {
        $this->client->update([
            'index' => 'orders',
            'id' => $order->id(),
            'body' => ['doc' => $order->getIndexArray()],
        ]);
    }
}
