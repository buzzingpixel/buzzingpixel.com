<?php

declare(strict_types=1);

namespace App\Context\Licenses\Schedule;

use App\Context\Licenses\Services\UpdateMaxVersionOnLicenses\UpdateMaxVersionOnLicenses;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class UpdateMaxVersionOnLicensesSchedule
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::DAY_AT_MIDNIGHT,
        );
    }

    public function __construct(
        private UpdateMaxVersionOnLicenses $updateMaxVersionOnLicenses
    ) {
    }

    public function __invoke(): void
    {
        $this->updateMaxVersionOnLicenses->update();
    }
}
