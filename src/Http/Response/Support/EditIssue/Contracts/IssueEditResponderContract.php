<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Contracts;

use App\Http\Response\Support\Entities\GetIssueResults;
use Psr\Http\Message\ResponseInterface;

interface IssueEditResponderContract
{
    public function respond(
        GetIssueResults $getIssueResults,
    ): ResponseInterface;
}
