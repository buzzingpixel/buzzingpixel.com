<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services;

use Elasticsearch\Client;
use Throwable;

class SetUpIndices
{
    public function __construct(private Client $client)
    {
    }

    public function setUp(): void
    {
        try {
            $this->client->indices()->get(['index' => 'issues']);
        } catch (Throwable) {
            $this->client->indices()->create(
                ['index' => 'issues']
            );
        }

        try {
            $this->client->indices()->get(['index' => 'users']);
        } catch (Throwable) {
            $this->client->indices()->create(
                ['index' => 'users']
            );
        }

        try {
            $this->client->indices()->get(['index' => 'orders']);
        } catch (Throwable) {
            $this->client->indices()->create(
                ['index' => 'orders']
            );
        }

        try {
            $this->client->indices()->get(['index' => 'licenses']);
        } catch (Throwable) {
            $this->client->indices()->create(
                ['index' => 'licenses']
            );
        }
    }
}
