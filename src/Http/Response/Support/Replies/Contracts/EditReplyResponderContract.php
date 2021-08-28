<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Contracts;

use App\Http\Response\Support\Entities\GetReplyResults;
use Psr\Http\Message\ResponseInterface;

interface EditReplyResponderContract
{
    public function respond(GetReplyResults $results): ResponseInterface;
}
