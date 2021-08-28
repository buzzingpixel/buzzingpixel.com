<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Factories;

use App\Context\Issues\Entities\IssueMessage;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Entities\GetReplyResults;

class ReplyFromIssueResultsFactory
{
    public function getReply(
        GetIssueResults $results,
        string $replyId,
    ): GetReplyResults {
        if ($results->hasNoIssue()) {
            return new GetReplyResults(reply: null);
        }

        return new GetReplyResults(
            reply: $results->issue()->issueMessages()->filter(
                static fn (IssueMessage $i) => $i->id() === $replyId,
            )->firstOrNull()
        );
    }
}
