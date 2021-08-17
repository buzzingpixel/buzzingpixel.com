<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Events\SaveIssueFailed;
use App\Context\Issues\Services\SaveIssue\Contracts\ExceptionHandlerContract;
use App\Payload\Payload;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandlerProd implements ExceptionHandlerContract
{
    public function __construct(
        private LoggerInterface $logger,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handle(
        Issue $issue,
        Throwable $exception,
    ): Payload {
        $this->logger->emergency(
            'An exception was caught saving an issue',
            ['exception' => $exception],
        );

        $this->eventDispatcher->dispatch(new SaveIssueFailed(
            issue: $issue,
            exception: $exception,
        ));

        return new Payload(
            status: Payload::STATUS_ERROR,
            result: ['message' => $exception->getMessage()],
        );
    }
}
