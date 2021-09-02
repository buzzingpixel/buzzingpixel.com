<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\Downloads\Responders;

use App\Context\Software\Entities\SoftwareVersion;
use App\Http\Response\Account\Licenses\Downloads\Contracts\DownloadResponderContract;
use App\Http\Response\Downloads\SoftwareFileDownload\SoftwareFileDownload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;

class DownloadResponder implements DownloadResponderContract
{
    public function __construct(
        private SoftwareFileDownload $softwareFileDownload,
    ) {
    }

    public function respond(
        ?SoftwareVersion $version,
        ServerRequestInterface $request,
    ): ResponseInterface {
        assert($version instanceof SoftwareVersion);

        return $this->softwareFileDownload->download(
            version: $version,
            request: $request
        );
    }
}
