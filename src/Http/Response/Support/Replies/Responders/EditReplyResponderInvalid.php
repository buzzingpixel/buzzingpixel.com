<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Responders;

use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Replies\Contracts\EditReplyResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class EditReplyResponderInvalid implements EditReplyResponderContract
{
    public function __construct(private ServerRequestInterface $request)
    {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function respond(GetReplyResults $results): ResponseInterface
    {
        throw new HttpNotFoundException($this->request);
    }
}
