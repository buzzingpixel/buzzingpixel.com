<?php

declare(strict_types=1);

namespace App\Context\Analytics\Factories;

use App\Context\Analytics\Contracts\GetUriStatsContract;
use App\Context\Analytics\Services\GetUriStatsNoDate;
use App\Context\Analytics\Services\GetUriStatsSinceDate;
use DateTimeImmutable;

class GetUriStatsFactory
{
    public function __construct(
        private GetUriStatsNoDate $getUriStatsNoDate,
        private GetUriStatsSinceDate $getUriStatsSinceDate,
    ) {
    }

    public function create(?DateTimeImmutable $date = null): GetUriStatsContract
    {
        if ($date === null) {
            return $this->getUriStatsNoDate;
        }

        return $this->getUriStatsSinceDate;
    }
}
