<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexUser\Factories;

use App\Context\ElasticSearch\Services\IndexUser\Contracts\IndexUserContract;
use App\Context\ElasticSearch\Services\IndexUser\Services\IndexUserCreate;
use App\Context\ElasticSearch\Services\IndexUser\Services\IndexUserExisting;
use App\Context\Users\Entities\User;
use Elasticsearch\Client;
use Throwable;

class IndexUserFactory
{
    public function __construct(
        private Client $client,
        private IndexUserCreate $create,
        private IndexUserExisting $existing,
    ) {
    }

    public function make(User $user): IndexUserContract
    {
        try {
            $this->client->get([
                'index' => 'users',
                'id' => $user->id(),
            ]);

            return $this->existing;
        } catch (Throwable) {
            return $this->create;
        }
    }
}
