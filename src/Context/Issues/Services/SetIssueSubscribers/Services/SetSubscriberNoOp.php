<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SetIssueSubscribers\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SetIssueSubscribers\Contracts\SetSubscriberContract;
use App\Context\Users\Entities\User;

class SetSubscriberNoOp implements SetSubscriberContract
{
    public function set(Issue $issue, User $user): void
    {
    }
}
