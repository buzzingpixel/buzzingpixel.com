<?php

declare(strict_types=1);

namespace App\Context\Queue\Schedule;

use App\Context\Queue\Services\CleanDeadQueues as CleanDeadQueuesService;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class CleanDeadQueues
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::ALWAYS,
        );
    }

    public function __construct(private CleanDeadQueuesService $cleanDeadQueues)
    {
    }

    public function __invoke(): void
    {
        $this->cleanDeadQueues->clean();
    }
}
