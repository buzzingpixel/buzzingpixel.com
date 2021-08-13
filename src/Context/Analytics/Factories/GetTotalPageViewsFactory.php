<?php

declare(strict_types=1);

namespace App\Context\Analytics\Factories;

use App\Context\Analytics\Contracts\GetTotalPageViewsContract;
use App\Context\Analytics\Services\GetTotalPageViewsNoDate;
use App\Context\Analytics\Services\GetTotalPageViewsSinceDate;
use DateTimeImmutable;

class GetTotalPageViewsFactory
{
    public function __construct(
        private GetTotalPageViewsNoDate $getTotalPageViewsNoDate,
        private GetTotalPageViewsSinceDate $getTotalPageViewsSinceDate,
    ) {
    }

    public function create(?DateTimeImmutable $date = null): GetTotalPageViewsContract
    {
        if ($date === null) {
            return $this->getTotalPageViewsNoDate;
        }

        return $this->getTotalPageViewsSinceDate;
    }
}
