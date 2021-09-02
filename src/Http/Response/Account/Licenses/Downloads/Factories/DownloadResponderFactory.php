<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\Downloads\Factories;

use App\Context\Software\Entities\SoftwareVersion;
use App\Http\Response\Account\Licenses\Downloads\Contracts\DownloadResponderContract;
use App\Http\Response\Account\Licenses\Downloads\Responders\DownloadResponder;
use App\Http\Response\Account\Licenses\Downloads\Responders\DownloadResponderInvalid;

class DownloadResponderFactory
{
    public function __construct(
        private DownloadResponder $responder,
        private DownloadResponderInvalid $responderInvalid,
    ) {
    }

    public function getResponder(
        ?SoftwareVersion $version,
    ): DownloadResponderContract {
        if ($version === null) {
            return $this->responderInvalid;
        }

        return $this->responder;
    }
}
