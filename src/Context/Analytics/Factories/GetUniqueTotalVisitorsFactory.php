<?php

declare(strict_types=1);

namespace App\Context\Analytics\Factories;

use App\Context\Analytics\Contracts\GetUniqueTotalVisitorsContract;
use App\Context\Analytics\Services\GetUniqueTotalVisitorsNoDate;
use App\Context\Analytics\Services\GetUniqueTotalVisitorsSinceDate;
use DateTimeImmutable;

class GetUniqueTotalVisitorsFactory
{
    public function __construct(
        private GetUniqueTotalVisitorsNoDate $getUniqueTotalVisitorsNoDate,
        private GetUniqueTotalVisitorsSinceDate $getUniqueTotalVisitorsSinceDate,
    ) {
    }

    public function create(
        ?DateTimeImmutable $date = null,
    ): GetUniqueTotalVisitorsContract {
        if ($date === null) {
            return $this->getUniqueTotalVisitorsNoDate;
        }

        return $this->getUniqueTotalVisitorsSinceDate;
    }
}
