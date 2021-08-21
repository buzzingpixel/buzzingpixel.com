<?php

declare(strict_types=1);

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

return [
    Client::class => static function (): Client {
        /** @phpstan-ignore-next-line */
        $hostsString = (string) getenv('ELASTIC_SEARCH_HOSTS');

        $hosts = explode(',', $hostsString);

        return ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    },
];
