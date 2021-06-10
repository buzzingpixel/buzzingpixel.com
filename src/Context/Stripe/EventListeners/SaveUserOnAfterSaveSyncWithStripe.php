<?php

declare(strict_types=1);

namespace App\Context\Stripe\EventListeners;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Stripe\QueueActions\SyncUserWithStripeQueueAction;
use App\Context\Users\Events\SaveUserAfterSave;

class SaveUserOnAfterSaveSyncWithStripe
{
    public function __construct(private QueueApi $queueApi)
    {
    }

    public function onAfterSave(SaveUserAfterSave $event): void
    {
        $this->queueApi->addToQueue(
            (new Queue())
                ->withHandle('sync-user-with-stripe')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItem(
                        className: SyncUserWithStripeQueueAction::class,
                        methodName: 'sync',
                        context: ['userId' => $event->userEntity->id()],
                    ),
                ),
        );
    }
}
