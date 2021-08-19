<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalIssues;

use Throwable;

class ExceptionHandlerDev implements Contracts\ExceptionHandlerContract
{
    /**
     * @throws Throwable
     */
    public function handle(Throwable $exception): int
    {
        throw $exception;
    }
}
