<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\EditReply;

use App\Context\Issues\IssuesApi;
use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Replies\Contracts\EditReplyContract;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;

class EditReply implements EditReplyContract
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    public function edit(
        GetReplyResults $results,
        IssueReplyFormValues $formValues,
    ): Payload {
        $reply = $results->reply()
            ->withMessage(message: $formValues->comment()->toString())
            ->withUpdatedNow();

        $issue = $reply->issue();

        $issue->issueMessages()->replaceWhereMatch(
            'id',
            $reply,
        );

        return $this->issuesApi->saveIssue(issue: $issue);
    }
}
