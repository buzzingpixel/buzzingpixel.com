<?php

declare(strict_types=1);

namespace App\Context\Analytics\Entities;

class UriStats
{
    public function __construct(
        private string $uri = '',
        private int $totalVisitorsInTimeRange = 0,
        private int $totalUniqueVisitorsInTimeRange = 0,
    ) {
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function totalVisitorsInTimeRange(): int
    {
        return $this->totalVisitorsInTimeRange;
    }

    public function totalUniqueVisitorsInTimeRange(): int
    {
        return $this->totalUniqueVisitorsInTimeRange;
    }
}
