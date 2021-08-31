<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Factories;

use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Users\Entities\User;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\UnsubscribeContract;
use App\Http\Response\Support\Subscribe\Services\Unsubscribe;
use App\Http\Response\Support\Subscribe\Services\UnsubscribeNoOp;

class UnsubscribeFactory
{
    public function __construct(
        private Unsubscribe $unsubscribe,
        private UnsubscribeNoOp $unsubscribeNoOp,
    ) {
    }

    public function getUnsubscribe(
        User $user,
        GetIssueResults $results,
    ): UnsubscribeContract {
        if ($results->hasNoIssue()) {
            return $this->unsubscribeNoOp;
        }

        $existingSubscriber = $results->issue()->issueSubscribers()->filter(
            static fn (
                IssueSubscriber $i
            ) => $i->userGuarantee()->id() === $user->id(),
        )->firstOrNull();

        if ($existingSubscriber === null) {
            return $this->unsubscribeNoOp;
        }

        return $this->unsubscribe;
    }
}
