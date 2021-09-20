<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexUser\Services;

use App\Context\ElasticSearch\Services\IndexUser\Contracts\IndexUserContract;
use App\Context\Users\Entities\User;
use Elasticsearch\Client;

class IndexUserExisting implements IndexUserContract
{
    public function __construct(private Client $client)
    {
    }

    public function indexUser(User $user): void
    {
        $this->client->update([
            'index' => 'users',
            'id' => $user->id(),
            'body' => ['doc' => $user->getIndexArray()],
        ]);
    }
}
