<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Factories;

use App\Context\Issues\Services\SaveIssue\Contracts\ExceptionHandlerContract;
use App\Context\Issues\Services\SaveIssue\ExceptionHandlerDev;
use App\Context\Issues\Services\SaveIssue\ExceptionHandlerProd;
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
