<?php

declare(strict_types=1);

namespace Config;

use App\Context\Queue\Schedule\CleanDeadQueues;
use App\Context\Queue\Schedule\CleanOldQueues;
use App\Context\Schedule\Entities\ScheduleConfigItemCollection;
use App\Context\Users\Schedule\UserResetTokenGarbageCollection;

class ScheduleConfig
{
    /** @phpstan-ignore-next-line  */
    public static function getSchedule(): ScheduleConfigItemCollection
    {
        return new ScheduleConfigItemCollection([
            CleanDeadQueues::getScheduleConfig(),
            CleanOldQueues::getScheduleConfig(),
            UserResetTokenGarbageCollection::getScheduleConfig(),
        ]);
    }
}
