<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueDisplay\Responders;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\IssueDisplay\Contracts\IssueDisplayResponderContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class IssueDisplayResponderInvalid implements IssueDisplayResponderContract
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
