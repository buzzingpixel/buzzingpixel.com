<?php

declare(strict_types=1);

namespace App\Utilities;

use DateTimeZone;
use Safe\DateTimeImmutable;

class SystemClock
{
    public function getCurrentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function getCurrentTimeUtc(): DateTimeImmutable
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getCurrentTime()->setTimezone(
            new DateTimeZone('UTC'),
        );
    }
}
