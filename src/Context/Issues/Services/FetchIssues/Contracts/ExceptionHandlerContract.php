<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchIssues\Contracts;

use App\Context\Issues\Entities\IssueCollection;
use Throwable;

interface ExceptionHandlerContract
{
    /** @phpstan-ignore-next-line  */
    public function handle(Throwable $exception): IssueCollection;
}
