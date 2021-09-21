<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrder\Factories;

use App\Context\ElasticSearch\Services\IndexOrder\Contracts\IndexOrderContract;
use App\Context\ElasticSearch\Services\IndexOrder\Services\IndexOrderCreate;
use App\Context\ElasticSearch\Services\IndexOrder\Services\IndexOrderExisting;
use App\Context\Orders\Entities\Order;
use Elasticsearch\Client;
use Throwable;

class IndexOrderFactory
{
    public function __construct(
        private Client $client,
        private IndexOrderCreate $create,
        private IndexOrderExisting $existing,
    ) {
    }

    public function make(Order $order): IndexOrderContract
    {
        try {
            $this->client->get([
                'index' => 'orders',
                'id' => $order->id(),
            ]);

            return $this->existing;
        } catch (Throwable) {
            return $this->create;
        }
    }
}
