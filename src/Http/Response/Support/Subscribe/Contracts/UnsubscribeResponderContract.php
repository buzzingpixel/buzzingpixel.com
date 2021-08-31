<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Contracts;

use App\Http\Response\Support\Entities\GetIssueResults;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface UnsubscribeResponderContract
{
    public function respond(
        int $issueNumber,
        GetIssueResults $results,
        ServerRequestInterface $request,
    ): ResponseInterface;
}
