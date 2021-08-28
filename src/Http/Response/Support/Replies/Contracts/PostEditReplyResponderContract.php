<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Contracts;

use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;

interface PostEditReplyResponderContract
{
    public function respond(
        Payload $payload,
        int $issueNumber,
        string $replyId,
    ): ResponseInterface;
}
