<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Factories\GetUniqueTotalVisitorsFactory;
use DateTimeImmutable;

class GetUniqueTotalVisitorsSince
{
    public function __construct(
        private GetUniqueTotalVisitorsFactory $getUniqueTotalVisitorsFactory,
    ) {
    }

    public function get(?DateTimeImmutable $date = null): int
    {
        return $this->getUniqueTotalVisitorsFactory->create(
            date: $date,
        )->get(date: $date);
    }
}
