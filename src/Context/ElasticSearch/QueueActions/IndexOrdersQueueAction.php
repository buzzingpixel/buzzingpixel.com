<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\QueueActions;

use App\Context\ElasticSearch\ElasticSearchApi;

class IndexOrdersQueueAction
{
    public function __construct(private ElasticSearchApi $elasticSearchApi)
    {
    }

    public function __invoke(): void
    {
        $this->elasticSearchApi->indexOrders();
    }
}
