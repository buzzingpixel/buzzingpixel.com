<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\EditIssue\Contracts\IssueEditResponderContract;
use App\Http\Response\Support\EditIssue\Responders\IssueEditResponderAdmin;
use App\Http\Response\Support\EditIssue\Responders\IssueEditResponderInvalid;
use App\Http\Response\Support\EditIssue\Responders\IssueEditResponderUser;
use App\Http\Response\Support\Entities\GetIssueResults;
use Psr\Http\Message\ServerRequestInterface;

class IssueEditResponderFactory
{
    public function __construct(
        private IssueEditResponderUser $user,
        private IssueEditResponderAdmin $admin,
    ) {
    }

    public function getResponder(
        LoggedInUser $loggedInUser,
        ServerRequestInterface $request,
        GetIssueResults $getIssueResults,
    ): IssueEditResponderContract {
        if ($loggedInUser->hasNoUser() || $getIssueResults->hasNoIssue()) {
            return new IssueEditResponderInvalid(request: $request);
        }

        if ($loggedInUser->user()->isAdmin()) {
            return $this->admin;
        }

        $issueUserId = $getIssueResults->issue()->userGuarantee()->id();

        if ($loggedInUser->user()->id() === $issueUserId) {
            return $this->user;
        }

        return new IssueEditResponderInvalid(request: $request);
    }
}
