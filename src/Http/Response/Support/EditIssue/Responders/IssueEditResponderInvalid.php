<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Responders;

use App\Http\Response\Support\EditIssue\Contracts\IssueEditResponderContract;
use App\Http\Response\Support\Entities\GetIssueResults;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class IssueEditResponderInvalid implements IssueEditResponderContract
{
    public function __construct(
        private ServerRequestInterface $request
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function respond(
        GetIssueResults $getIssueResults,
    ): ResponseInterface {
        throw new HttpNotFoundException($this->request);
    }
}
