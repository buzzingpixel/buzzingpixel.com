<?php

declare(strict_types=1);

namespace App\Context\Analytics\Contracts;

use App\Context\Analytics\Entities\UriStatsCollection;
use DateTimeImmutable;

interface GetUriStatsContract
{
    /** @phpstan-ignore-next-line */
    public function get(?DateTimeImmutable $date = null): UriStatsCollection;
}
