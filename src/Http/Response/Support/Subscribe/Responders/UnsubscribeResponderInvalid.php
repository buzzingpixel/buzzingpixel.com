<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Responders;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\UnsubscribeResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class UnsubscribeResponderInvalid implements UnsubscribeResponderContract
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
