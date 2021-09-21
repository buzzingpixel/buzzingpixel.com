<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexLicense\Factories;

use App\Context\ElasticSearch\Services\IndexLicense\Contracts\IndexLicenseContract;
use App\Context\ElasticSearch\Services\IndexLicense\Services\IndexLicenseCreate;
use App\Context\ElasticSearch\Services\IndexLicense\Services\IndexLicenseExisting;
use App\Context\Licenses\Entities\License;
use Elasticsearch\Client;
use Throwable;

class IndexLicenseFactory
{
    public function __construct(
        private Client $client,
        private IndexLicenseCreate $create,
        private IndexLicenseExisting $existing,
    ) {
    }

    public function make(License $license): IndexLicenseContract
    {
        try {
            $this->client->get([
                'index' => 'licenses',
                'id' => $license->id(),
            ]);

            return $this->existing;
        } catch (Throwable) {
            return $this->create;
        }
    }
}
