<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Schedule;

use App\Context\ElasticSearch\ElasticSearchApi;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;
use Throwable;

class SetUpIndicesSchedule
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::FIVE_MINUTES,
        );
    }

    public function __construct(private ElasticSearchApi $elasticSearchApi)
    {
    }

    public function __invoke(): void
    {
        try {
            $this->elasticSearchApi->setUpIndices();
        } catch (Throwable) {
        }
    }
}
