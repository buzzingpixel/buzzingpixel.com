<?php

declare(strict_types=1);

namespace App\Context\Stripe\Schedule;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Schedule\Entities\ScheduleConfigItem;
use App\Context\Schedule\Frequency;
use App\Context\Stripe\QueueActions\SyncAllStripeItemsQueueAction;

class StripeSyncEveryNight
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
                ->withHandle('sync-all-stripe-items')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItem(
                        className: SyncAllStripeItemsQueueAction::class,
                    ),
                ),
        );
    }
}
