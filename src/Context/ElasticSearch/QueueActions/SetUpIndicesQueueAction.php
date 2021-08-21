<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\QueueActions;

use App\Context\ElasticSearch\ElasticSearchApi;
use Throwable;

class SetUpIndicesQueueAction
{
    public function __construct(private ElasticSearchApi $elasticSearchApi)
    {
    }

    public function __invoke(): void
    {
        try {
            $this->elasticSearchApi->setUpIndices();
        } catch (Throwable) {
        }
    }
}
