<?php

declare(strict_types=1);

namespace App\Context\Queue\Services;

use App\Context\Queue\Entities\QueueEntity;
use DateTimeImmutable;
use DateTimeZone;
use Throwable;

use function get_class;

use const PHP_EOL;

class MarkQueueItemStoppedDueToError
{
    public function __construct(private SaveQueue $saveQueue)
    {
    }

    public function mark(
        QueueEntity $queue,
        ?Throwable $e = null,
    ): void {
        $finishedAt = new DateTimeImmutable(
            timezone: new DateTimeZone('UTC'),
        );

        $msg = '';

        if ($e !== null) {
            $eol  = PHP_EOL . PHP_EOL;
            $msg .= 'Exception Type: ' . get_class($e) . $eol;
            $msg .= 'Error Code: ' . $e->getCode() . $eol;
            $msg .= 'File: ' . $e->getFile() . $eol;
            $msg .= 'Line: ' . $e->getLine() . $eol;
            $msg .= 'Message: ' . $e->getMessage() . $eol;
            $msg .= 'Trace . ' . $e->getTraceAsString();
        }

        $this->saveQueue->save(
            $queue->withIsRunning(false)
                ->withIsFinished()
                ->withFinishedDueToError()
                ->withErrorMessage($msg)
                ->withFinishedAt($finishedAt),
        );
    }
}
