<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\FetchTotalIssues\Factories;

use App\Context\Issues\Services\FetchTotalIssues\Contracts\ExceptionHandlerContract;
use App\Context\Issues\Services\FetchTotalIssues\ExceptionHandlerDev;
use App\Context\Issues\Services\FetchTotalIssues\ExceptionHandlerProd;
use Config\General;

class ExceptionHandlerFactory
{
    public function __construct(
        private General $config,
        private ExceptionHandlerDev $exceptionHandlerDev,
        private ExceptionHandlerProd $exceptionHandlerProd,
    ) {
    }

    public function getExceptionHandler(): ExceptionHandlerContract
    {
        if ($this->config->devMode()) {
            return $this->exceptionHandlerDev;
        }

        return $this->exceptionHandlerProd;
    }
}
