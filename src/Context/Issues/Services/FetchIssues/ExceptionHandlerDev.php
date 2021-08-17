<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchIssues;

use App\Context\Issues\Entities\IssueCollection;
use Throwable;

class ExceptionHandlerDev implements Contracts\ExceptionHandlerContract
{
    /**
     * @throws Throwable
     *
     * @phpstan-ignore-next-line
     */
    public function handle(Throwable $exception): IssueCollection
    {
        throw $exception;
    }
}
