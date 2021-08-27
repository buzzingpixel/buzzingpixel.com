<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueDisplay\Factory;

use App\Http\Response\Support\IssueDisplay\Contracts\IssueDisplayResponderContract;
use App\Http\Response\Support\IssueDisplay\Entities\GetIssueResults;
use App\Http\Response\Support\IssueDisplay\Responders\IssueDisplayResponder;
use App\Http\Response\Support\IssueDisplay\Responders\IssueDisplayResponderInvalid;
use Psr\Http\Message\ServerRequestInterface;

class IssueDisplayResponderFactory
{
    public function __construct(
        private IssueDisplayResponder $responder,
    ) {
    }

    public function getResponder(
        ServerRequestInterface $request,
        GetIssueResults $getIssueResults,
    ): IssueDisplayResponderContract {
        if ($getIssueResults->hasNoIssue()) {
            return new IssueDisplayResponderInvalid(request: $request);
        }

        return $this->responder;
    }
}
