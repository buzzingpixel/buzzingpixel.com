<?php

declare(strict_types=1);

namespace App\Context\Schedule\Services;

use App\Context\Schedule\Entities\ScheduleItem;
use App\Context\Schedule\Frequency;
use App\Utilities\SystemClock;
use Throwable;

use function is_numeric;

class CheckIfScheduleShouldRun
{
    public function __construct(private SystemClock $systemClock)
    {
    }

    public function check(ScheduleItem $scheduleItem): bool
    {
        try {
            return $this->innerCheck($scheduleItem);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function innerCheck(ScheduleItem $scheduleItem): bool
    {
        $currentTime = $this->systemClock->getCurrentTime();

        $currentTimeStamp = $currentTime->getTimestamp();

        $lastRunStartAt = $scheduleItem->lastRunStartAt();

        $lastRunTimeStamp = 0;

        if ($lastRunStartAt !== null) {
            $lastRunTimeStamp = $lastRunStartAt->getTimestamp();
        }

        $oneHourInSeconds = 60 * 60;

        $secondsSinceLastRun = $currentTimeStamp - $lastRunTimeStamp;

        $runEvery = Frequency::getTranslatedValue(
            $scheduleItem->runEvery()
        );

        // If the task is running, wait one hour before trying again
        if (
            $secondsSinceLastRun < $oneHourInSeconds &&
            $scheduleItem->isRunning()
        ) {
            return false;
        }

        // If $runEvery is numeric we'll check if it's time to run based on that
        if (is_numeric($runEvery)) {
            $runEvery = (int) $runEvery;

            return $secondsSinceLastRun >= $runEvery;
        }

        /**
         * Now we know it's a midnight string and we're checking for that
         */

        // Increment timestamp by 20 hours
        $incrementTime = $lastRunTimeStamp + 72000;

        /**
         * Don't run if it hasn't been more than 20 hours (we're trying to
         * hit the right window, but we can't be too precise because what if
         * the cron doesn't run right at midnight. But we also only want to
         * run this once)
         */
        if ($incrementTime > $currentTimeStamp) {
            return false;
        }

        // If the hour is not in the midnight range, we know we can stop
        if ($currentTime->format('H') !== '00') {
            return false;
        }

        // Now if we're running every day, we know it's time to run
        if ($runEvery === 'dayatmidnight') {
            return true;
        }

        $day = $currentTime->format('l');

        // If we're running on Saturday, and it's Saturday, we should run
        if ($runEvery === 'saturdayatmidnight' && $day === 'Saturday') {
            return true;
        }

        // If we're running on Sunday, and it's Sunday, we should run
        if ($runEvery === 'sundayatmidnight' && $day === 'Sunday') {
            return true;
        }

        // If we're running on Monday, and it's Monday, we should run
        if ($runEvery === 'mondayatmidnight' && $day === 'Monday') {
            return true;
        }

        // If we're running on Monday, and it's Monday, we should run
        if ($runEvery === 'tuesdayatmidnight' && $day === 'Tuesday') {
            return true;
        }

        // If we're running on Monday, and it's Monday, we should run
        if ($runEvery === 'wednesdayatmidnight' && $day === 'Wednesday') {
            return true;
        }

        // If we're running on Monday, and it's Monday, we should run
        if ($runEvery === 'thursdayatmidnight' && $day === 'Thursday') {
            return true;
        }

        // If we're running on Friday, and it's Friday, we should run
        return $runEvery === 'fridayatmidnight' && $day === 'Friday';
    }
}
