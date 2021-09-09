<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Contracts;

use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;

interface ResponderContract
{
    public function respond(Payload $payload): ResponseInterface;
}
