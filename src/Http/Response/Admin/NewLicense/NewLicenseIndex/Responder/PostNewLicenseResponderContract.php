<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\Responder;

use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;

interface PostNewLicenseResponderContract
{
    public function respond(Payload $payload): ResponseInterface;
}
