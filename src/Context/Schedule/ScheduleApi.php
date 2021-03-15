<?php

declare(strict_types=1);

namespace App\Context\Schedule;

use App\Context\Schedule\Entities\ScheduleItem;
use App\Context\Schedule\Services\CheckIfScheduleShouldRun;

class ScheduleApi
{
    public function __construct(
        private CheckIfScheduleShouldRun $checkIfScheduleShouldRun,
    ) {
    }

    public function checkIfScheduleShouldRun(ScheduleItem $scheduleItem): bool
    {
        return $this->checkIfScheduleShouldRun->check(
            $scheduleItem
        );
    }
}
