<?php

declare(strict_types=1);

namespace App\Utilities;

use Safe\DateTimeImmutable;

class SystemClock
{
    public function getCurrentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
