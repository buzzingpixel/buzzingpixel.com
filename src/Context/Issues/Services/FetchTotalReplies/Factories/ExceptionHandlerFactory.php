<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalReplies\Factories;

use App\Context\Issues\Services\FetchTotalReplies\Contracts\ExceptionHandlerContract;
use App\Context\Issues\Services\FetchTotalReplies\ExceptionHandlers\ExceptionHandlerDev;
use App\Context\Issues\Services\FetchTotalReplies\ExceptionHandlers\ExceptionHandlerProd;
use Config\General;

class ExceptionHandlerFactory
{
    public function __construct(
        private General $config,
        private ExceptionHandlerDev $dev,
        private ExceptionHandlerProd $prod,
    ) {
    }

    public function getExceptionHandler(): ExceptionHandlerContract
    {
        if ($this->config->devMode()) {
            return $this->dev;
        }

        return $this->prod;
    }
}
