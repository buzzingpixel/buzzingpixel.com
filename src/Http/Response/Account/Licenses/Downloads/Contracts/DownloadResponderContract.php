<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\Downloads\Contracts;

use App\Context\Software\Entities\SoftwareVersion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface DownloadResponderContract
{
    public function respond(
        ?SoftwareVersion $version,
        ServerRequestInterface $request,
    ): ResponseInterface;
}
