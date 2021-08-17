<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchIssues;

use App\Context\Issues\Entities\IssueCollection;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandlerProd implements Contracts\ExceptionHandlerContract
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /** @phpstan-ignore-next-line  */
    public function handle(Throwable $exception): IssueCollection
    {
        $this->logger->emergency(
            'An exception was caught querying for Issues',
            ['exception' => $exception],
        );

        return new IssueCollection();
    }
}
