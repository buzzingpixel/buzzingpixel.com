<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Contracts;

use App\Context\Issues\Entities\Issue;
use App\Payload\Payload;
use Throwable;

interface ExceptionHandlerContract
{
    public function handle(
        Issue $issue,
        Throwable $exception,
    ): Payload;
}
