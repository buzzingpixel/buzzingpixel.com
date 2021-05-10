<?php

declare(strict_types=1);

namespace App\Context\Cart\Schedule;

use App\Context\Cart\Services\CleanOldCarts as CleanOldCartsService;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class CleanOldCarts
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::DAY_AT_MIDNIGHT,
        );
    }

    public function __construct(private CleanOldCartsService $cleanOldCarts)
    {
    }

    public function __invoke(): void
    {
        $this->cleanOldCarts->clean();
    }
}
