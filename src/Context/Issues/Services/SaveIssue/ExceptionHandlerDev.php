<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssue\Contracts\ExceptionHandlerContract;
use App\Payload\Payload;
use Throwable;

class ExceptionHandlerDev implements ExceptionHandlerContract
{
    /**
     * @throws Throwable
     */
    public function handle(
        Issue $issue,
        Throwable $exception,
    ): Payload {
        throw $exception;
    }
}
