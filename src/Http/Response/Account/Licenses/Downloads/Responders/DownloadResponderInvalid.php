<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\Downloads\Responders;

use App\Context\Software\Entities\SoftwareVersion;
use App\Http\Response\Account\Licenses\Downloads\Contracts\DownloadResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class DownloadResponderInvalid implements DownloadResponderContract
{
    /**
     * @throws HttpNotFoundException
     */
    public function respond(
        ?SoftwareVersion $version,
        ServerRequestInterface $request,
    ): ResponseInterface {
        throw new HttpNotFoundException($request);
    }
}
