<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Contracts;

use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;

interface PostCreateNewIssueResponderContract
{
    public function respond(Payload $payload): ResponseInterface;
}
