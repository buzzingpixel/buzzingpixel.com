<?php

declare(strict_types=1);

namespace App\Context\Stripe\EventListeners;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Software\Events\SaveSoftwareAfterSave;
use App\Context\Stripe\QueueActions\SyncSoftwareWithStripeQueueAction;

class SaveSoftwareOnAfterSaveSyncWithStripe
{
    public function __construct(private QueueApi $queueApi)
    {
    }

    public function onAfterSave(SaveSoftwareAfterSave $event): void
    {
        $this->queueApi->addToQueue(
            (new Queue())
                ->withHandle('sync-software-with-stripe')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItem(
                        className: SyncSoftwareWithStripeQueueAction::class,
                        methodName: 'sync',
                        context: ['softwareId' => $event->software->id()],
                    ),
                ),
        );
    }
}
