<?php

declare(strict_types=1);

namespace App\Context\Email\Services;

use App\Context\Email\Entity\Email;
use App\Context\Queue\Entities\QueueEntity;
use App\Context\Queue\Entities\QueueItemEntity;
use App\Context\Queue\QueueApi;

use function serialize;

class QueueEmail
{
    public function __construct(private QueueApi $queueApi)
    {
    }

    public function queue(Email $email): void
    {
        $this->queueApi->addToQueue(
            (new QueueEntity())
                ->withHandle('send-email')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItemEntity(
                        className: SendQueueEmail::class,
                        methodName: 'send',
                        context: ['email' => serialize($email)]
                    ),
                )
        );
    }
}