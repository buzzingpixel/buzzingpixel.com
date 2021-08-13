<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Entities\UriStatsCollection;
use App\Context\Analytics\Factories\GetUriStatsFactory;
use DateTimeImmutable;

class GetUriStatsSince
{
    public function __construct(
        private GetUriStatsFactory $getUriStatsFactory,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function get(?DateTimeImmutable $date = null): UriStatsCollection
    {
        return $this->getUriStatsFactory->create(date: $date)->get(date: $date);
    }
}
