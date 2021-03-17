<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache\Schedule;

use App\Context\DatabaseCache\Services\PruneExpiredCache as PruneExpiredCacheService;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class PruneExpiredCache
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::ALWAYS,
        );
    }

    public function __construct(
        private PruneExpiredCacheService $pruneExpiredCache
    ) {
    }

    public function __invoke(): void
    {
        $this->pruneExpiredCache->prune();
    }
}
