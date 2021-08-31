<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Responders;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\SubscribeResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class SubscribeResponderInvalid implements SubscribeResponderContract
{
    /**
     * @throws HttpNotFoundException
     */
    public function respond(
        int $issueNumber,
        GetIssueResults $results,
        ServerRequestInterface $request,
    ): ResponseInterface {
        throw new HttpNotFoundException($request);
    }
}
