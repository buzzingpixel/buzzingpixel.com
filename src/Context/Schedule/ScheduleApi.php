<?php

declare(strict_types=1);

namespace App\Context\Schedule;

use App\Context\Schedule\Entities\ScheduleItem;
use App\Context\Schedule\Entities\ScheduleItemCollection;
use App\Context\Schedule\Services\CheckIfScheduleShouldRun;
use App\Context\Schedule\Services\FetchSchedules;
use App\Context\Schedule\Services\SaveScheduleItem;
use App\Payload\Payload;

class ScheduleApi
{
    public function __construct(
        private CheckIfScheduleShouldRun $checkIfScheduleShouldRun,
        private FetchSchedules $fetchSchedules,
        private SaveScheduleItem $saveScheduleItem,
    ) {
    }

    public function checkIfScheduleShouldRun(ScheduleItem $scheduleItem): bool
    {
        return $this->checkIfScheduleShouldRun->check(
            $scheduleItem
        );
    }

    /** @phpstan-ignore-next-line  */
    public function fetchSchedules(): ScheduleItemCollection
    {
        return $this->fetchSchedules->fetch();
    }

    public function saveScheduleItem(ScheduleItem $scheduleItem): Payload
    {
        return $this->saveScheduleItem->save($scheduleItem);
    }
}
