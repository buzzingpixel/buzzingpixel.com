<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Factories;

use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Users\Entities\User;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\SubscribeContract;
use App\Http\Response\Support\Subscribe\Services\SubscribeNew;
use App\Http\Response\Support\Subscribe\Services\SubscribeNoOp;
use App\Http\Response\Support\Subscribe\Services\SubscribeResume;

class SubscribeFactory
{
    public function __construct(
        private SubscribeNew $subscribeNew,
        private SubscribeNoOp $subscribeNoOp,
        private SubscribeResume $subscribeResume,
    ) {
    }

    public function getSubscribe(
        User $user,
        GetIssueResults $results,
    ): SubscribeContract {
        if ($results->hasNoIssue()) {
            return $this->subscribeNoOp;
        }

        $existingSubscriber = $results->issue()->issueSubscribers()->filter(
            static fn (
                IssueSubscriber $i
            ) => $i->userGuarantee()->id() === $user->id(),
        )->firstOrNull();

        if ($existingSubscriber === null) {
            return $this->subscribeNew;
        }

        return $this->subscribeResume;
    }
}
