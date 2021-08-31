<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Services;

use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\User;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\SubscribeContract;

class SubscribeResume implements SubscribeContract
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    public function subscribeUser(User $user, GetIssueResults $results): void
    {
        $issue = $results->issue();

        $subscriber = $issue->issueSubscribers()->filter(
            static fn (
                IssueSubscriber $i
            ) => $i->userGuarantee()->id() === $user->id(),
        )->first()->withIsActive(isActive: true);

        $issue->issueSubscribers()->replaceWhereMatch(
            'id',
            $subscriber,
        );

        $this->issuesApi->saveIssue(issue: $issue);
    }
}
