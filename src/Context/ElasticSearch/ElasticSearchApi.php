<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch;

use App\Context\ElasticSearch\Services\SetUpIndices;

class ElasticSearchApi
{
    public function __construct(
        private SetUpIndices $setUpIndices,
    ) {
    }

    public function setUpIndices(): void
    {
        $this->setUpIndices->setUp();
    }
}
