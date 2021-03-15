<?php

declare(strict_types=1);

namespace App\Context\Queue\Schedule;

use App\Context\Queue\Services\CleanOldQueues as CleanOldQueuesService;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class CleanOldQueues
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::DAY_AT_MIDNIGHT,
        );
    }

    public function __construct(private CleanOldQueuesService $cleanOldQueues)
    {
    }

    public function __invoke(): void
    {
        $this->cleanOldQueues->clean();
    }
}
