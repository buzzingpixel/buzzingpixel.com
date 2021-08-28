<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\AddReply;

use App\Context\Issues\Entities\IssueMessage;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Replies\Contracts\AddReplyContract;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;

class AddReply implements AddReplyContract
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    public function add(
        GetIssueResults $results,
        LoggedInUser $loggedInUser,
        IssueReplyFormValues $formValues,
    ): Payload {
        return $this->issuesApi->saveIssue(
            issue: $results->issue()->withAddedIssueMessage(
                newIssueMessage: new IssueMessage(
                    message: $formValues->comment()->toString(),
                    user: $loggedInUser->user(),
                ),
            ),
        );
    }
}
