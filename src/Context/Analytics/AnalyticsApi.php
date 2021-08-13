<?php

declare(strict_types=1);

namespace App\Context\Analytics;

use App\Context\Analytics\Entities\AnalyticsEntity;
use App\Context\Analytics\Entities\UriStatsCollection;
use App\Context\Analytics\Services\GetTotalPageViewsSince;
use App\Context\Analytics\Services\GetUniqueTotalVisitorsSince;
use App\Context\Analytics\Services\GetUriStatsSince;
use App\Context\Analytics\Services\SaveAnalytic;
use App\Payload\Payload;
use DateTimeImmutable;

class AnalyticsApi
{
    public function __construct(
        private SaveAnalytic $saveAnalytic,
        private GetTotalPageViewsSince $getTotalPageViewsSince,
        private GetUniqueTotalVisitorsSince $getUniqueTotalVisitorsSince,
        private GetUriStatsSince $getUriStatsSince,
    ) {
    }

    public function saveAnalytic(AnalyticsEntity $analytic): Payload
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->saveAnalytic->save(analytic: $analytic);
    }

    public function getTotalPageViewsSince(?DateTimeImmutable $date = null): int
    {
        return $this->getTotalPageViewsSince->get(date: $date);
    }

    public function getUniqueTotalVisitorsSince(
        ?DateTimeImmutable $date = null
    ): int {
        return $this->getUniqueTotalVisitorsSince->get(date: $date);
    }

    /** @phpstan-ignore-next-line */
    public function getUriStatsSince(
        ?DateTimeImmutable $date = null,
    ): UriStatsCollection {
        return $this->getUriStatsSince->get(date: $date);
    }
}
