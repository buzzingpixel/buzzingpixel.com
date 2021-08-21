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
    }
}
