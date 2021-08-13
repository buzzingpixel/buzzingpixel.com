<?php

declare(strict_types=1);

namespace App\Context\Analytics\Contracts;

use DateTimeImmutable;

interface GetUniqueTotalVisitorsContract
{
    public function get(?DateTimeImmutable $date = null): int;
}
