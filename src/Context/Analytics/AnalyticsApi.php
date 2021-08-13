<?php

declare(strict_types=1);

namespace App\Context\Analytics;

use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Context\Analytics\Services\GetTotalPageViewsSince;
use App\Context\Analytics\Services\GetUniqueTotalVisitorsSince;
use App\Context\Analytics\Services\SaveAnalytic;
use App\Payload\Payload;
use DateTimeImmutable;

class AnalyticsApi
{
    public function __construct(
        private SaveAnalytic $saveAnalytic,
        private GetTotalPageViewsSince $getTotalPageViewsSince,
        private GetUniqueTotalVisitorsSince $getUniqueTotalVisitorsSince,
    ) {
    }

    public function saveAnalytic(AnalyticsEntity $analytic): Payload
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->saveAnalytic->save($analytic);
    }

    public function getTotalPageViewsSince(?DateTimeImmutable $date = null): int
    {
        return $this->getTotalPageViewsSince->get($date);
    }

    public function getUniqueTotalVisitorsSince(
        ?DateTimeImmutable $date = null
    ): int {
        return $this->getUniqueTotalVisitorsSince->get($date);
    }
}
