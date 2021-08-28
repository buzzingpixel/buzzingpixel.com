<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalReplies\ExceptionHandlers;

use App\Context\Issues\Services\FetchTotalReplies\Contracts\ExceptionHandlerContract;
use Throwable;

class ExceptionHandlerDev implements ExceptionHandlerContract
{
    /**
     * @throws Throwable
     */
    public function handle(Throwable $exception): int
    {
        throw $exception;
    }
}
