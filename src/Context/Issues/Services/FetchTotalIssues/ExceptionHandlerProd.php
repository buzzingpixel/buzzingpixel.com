<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalIssues;

use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandlerProd implements Contracts\ExceptionHandlerContract
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function handle(Throwable $exception): int
    {
        $this->logger->emergency(
            'An exception was caught querying for total Issues',
            ['exception' => $exception],
        );

        return 0;
    }
}
