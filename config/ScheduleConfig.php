<?php

declare(strict_types=1);

namespace Config;

use App\Context\DatabaseCache\Schedule\PruneExpiredCache;
use App\Context\ElasticSearch\Schedule\SetUpIndicesSchedule;
use App\Context\Licenses\Schedule\UpdateMaxVersionOnLicensesSchedule;
use App\Context\Queue\Schedule\CleanDeadQueues;
use App\Context\Queue\Schedule\CleanOldQueues;
use App\Context\Schedule\Entities\ScheduleConfigItemCollection;
use App\Context\Stripe\Schedule\StripeSyncEveryNight;
use App\Context\TempFileStorage\Schedule\CleanUploadedFilesSchedule;
use App\Context\Users\Schedule\UserResetTokenGarbageCollection;
use App\Context\Users\Schedule\UserSessionGarbageCollection;

class ScheduleConfig
{
    /** @phpstan-ignore-next-line  */
    public static function getSchedule(): ScheduleConfigItemCollection
    {
        return new ScheduleConfigItemCollection([
            CleanDeadQueues::getScheduleConfig(),
            CleanOldQueues::getScheduleConfig(),
            CleanUploadedFilesSchedule::getScheduleConfig(),
            PruneExpiredCache::getScheduleConfig(),
            SetUpIndicesSchedule::getScheduleConfig(),
            StripeSyncEveryNight::getScheduleConfig(),
            UpdateMaxVersionOnLicensesSchedule::getScheduleConfig(),
            UserResetTokenGarbageCollection::getScheduleConfig(),
            UserSessionGarbageCollection::getScheduleConfig(),
        ]);
    }
}
