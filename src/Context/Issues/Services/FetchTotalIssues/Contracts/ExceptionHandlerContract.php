<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalIssues\Contracts;

use Throwable;

interface ExceptionHandlerContract
{
    public function handle(Throwable $exception): int;
}
