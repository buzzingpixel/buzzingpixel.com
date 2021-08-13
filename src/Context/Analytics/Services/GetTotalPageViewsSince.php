<?php

declare(strict_types=1);

namespace App\Context\Analytics\Services;

use App\Context\Analytics\Factories\GetTotalPageViewsFactory;
use DateTimeImmutable;

class GetTotalPageViewsSince
{
    public function __construct(
        private GetTotalPageViewsFactory $getTotalPageViewsFactory,
    ) {
    }

    public function get(?DateTimeImmutable $date = null): int
    {
        return $this->getTotalPageViewsFactory->create(
            date: $date
        )->get(date: $date);
    }
}
