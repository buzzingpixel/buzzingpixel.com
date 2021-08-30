<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SetIssueSubscribers\Contracts;

use App\Context\Issues\Entities\Issue;
use App\Context\Users\Entities\User;

interface SetSubscriberContract
{
    public function set(Issue $issue, User $user): void;
}
