<?php

declare(strict_types=1);

namespace App\Context\Analytics;

use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Context\Analytics\Services\SaveAnalytic;
use App\Payload\Payload;

class AnalyticsApi
{
    public function __construct(
        private SaveAnalytic $saveAnalytic,
    ) {
    }

    public function saveAnalytic(AnalyticsEntity $analytic): Payload
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->saveAnalytic->save($analytic);
    }
}
