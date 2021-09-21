<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexLicense\Services;

use App\Context\ElasticSearch\Services\IndexLicense\Contracts\IndexLicenseContract;
use App\Context\Licenses\Entities\License;
use Elasticsearch\Client;

class IndexLicenseExisting implements IndexLicenseContract
{
    public function __construct(private Client $client)
    {
    }

    public function indexLicense(License $license): void
    {
        $this->client->update([
            'index' => 'licenses',
            'id' => $license->id(),
            'body' => ['doc' => $license->getIndexArray()],
        ]);
    }
}
