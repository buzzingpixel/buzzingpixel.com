<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Schedule;

use App\Context\ElasticSearch\QueueActions\SetUpIndicesQueueAction;
use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;

class SetUpIndicesSchedule
{
    public static function getScheduleConfig(): ScheduleConfigItem
    {
        return new ScheduleConfigItem(
            className: self::class,
            runEvery: Frequency::FIVE_MINUTES,
        );
    }

    public function __construct(private QueueApi $queueApi)
    {
    }

    public function __invoke(): void
    {
        $this->queueApi->addToQueue(
            queue: (new Queue())
                ->withHandle(handle: 'set-up-indices')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItem(
                        className: SetUpIndicesQueueAction::class,
                    ),
                ),
        );
    }
}
