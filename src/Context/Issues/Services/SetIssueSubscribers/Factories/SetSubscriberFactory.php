<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SetIssueSubscribers\Factories;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Issues\Services\SetIssueSubscribers\Contracts\SetSubscriberContract;
use App\Context\Issues\Services\SetIssueSubscribers\Services\SetSubscriber;
use App\Context\Issues\Services\SetIssueSubscribers\Services\SetSubscriberNoOp;
use App\Context\Users\Entities\User;

class SetSubscriberFactory
{
    public function __construct(
        private SetSubscriber $setSubscriber,
        private SetSubscriberNoOp $setSubscriberNoOp,
    ) {
    }

    public function get(Issue $issue, User $user): SetSubscriberContract
    {
        $userInCollection = $issue->issueSubscribers()
            ->filter(
                static fn (
                    IssueSubscriber $i
                ) => $i->userGuarantee()->id() === $user->id(),
            )
            ->firstOrNull();

        if ($userInCollection !== null) {
            return $this->setSubscriberNoOp;
        }

        return $this->setSubscriber;
    }
}
