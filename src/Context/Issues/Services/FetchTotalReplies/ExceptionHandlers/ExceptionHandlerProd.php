<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalReplies\ExceptionHandlers;

use App\Context\Issues\Services\FetchTotalReplies\Contracts\ExceptionHandlerContract;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandlerProd implements ExceptionHandlerContract
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function handle(Throwable $exception): int
    {
        $this->logger->emergency(
            'An exception was caught querying for total issue replies',
            ['exception' => $exception],
        );

        return 0;
    }
}
