<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueDisplay\Contracts;

use App\Http\Response\Support\IssueDisplay\Entities\GetIssueResults;
use Psr\Http\Message\ResponseInterface;

interface IssueDisplayResponderContract
{
    public function respond(
        GetIssueResults $getIssueResults,
    ): ResponseInterface;
}
