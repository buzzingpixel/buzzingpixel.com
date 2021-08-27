<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Contracts;

use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;

interface PostIssueEditResponderContract
{
    public function respond(
        Payload $payload,
        int $issueNumber,
    ): ResponseInterface;
}
