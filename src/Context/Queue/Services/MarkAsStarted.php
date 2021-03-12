<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\Queue;
use Config\General;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;

use function assert;

class MarkAsStarted
{
    public function __construct(
        private General $config,
        private SaveQueue $saveQueue,
    ) {
    }

    public function mark(Queue $queue): Queue
    {
        try {
            return $this->innerMark($queue);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            return $queue;
        }
    }

    private function innerMark(Queue $queue): Queue
    {
        $queue = $queue->withHasStarted(true)
            ->withIsRunning(true);

        $diff = $queue->addedAt()->diff(
            $queue->initialAssumeDeadAfter()
        );

        $newAssumeDeadAfter = new DateTimeImmutable(
            timezone: new DateTimeZone('UTC'),
        );

        $newAssumeDeadAfter = $newAssumeDeadAfter->add($diff);

        /** @psalm-suppress MixedAssignment */
        $queue = $this->saveQueue
            ->save($queue->withAssumeDeadAfter(
                $newAssumeDeadAfter
            ))
            ->getResult()['queueEntity'];

        assert($queue instanceof Queue);

        return $queue;
    }
}
