<?php

declare(strict_types=1);

namespace Config;

use App\Context\Schedule\Entities\ScheduleConfigItemCollection;

class ScheduleConfig
{
    /** @phpstan-ignore-next-line  */
    public static function getSchedule(): ScheduleConfigItemCollection
    {
        return new ScheduleConfigItemCollection();
    }
}
