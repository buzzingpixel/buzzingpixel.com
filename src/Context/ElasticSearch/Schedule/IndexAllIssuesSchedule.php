<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Schedule;

use App\Context\ElasticSearch\QueueActions\IndexIssuesQueueAction;
use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class IndexAllIssuesSchedule
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::DAY_AT_MIDNIGHT,
        );
    }

    public function __construct(private QueueApi $queueApi)
    {
    }

    public function __invoke(): void
    {
        $this->queueApi->addToQueue(
            queue: (new Queue())
                ->withHandle(handle: 'index-all-issues')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItem(
                        className: IndexIssuesQueueAction::class,
                    ),
                ),
        );
    }
}
