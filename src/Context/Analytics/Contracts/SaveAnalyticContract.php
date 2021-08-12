<?php

declare(strict_types=1);

namespace App\Context\Analytics\Contracts;

use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Payload\Payload;
use App\Persistence\Entities\Analytics\AnalyticsRecord;

interface SaveAnalyticContract
{
    public function save(
        AnalyticsEntity $entity,
        ?AnalyticsRecord $record = null,
    ): Payload;
}
