<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Contracts\SetInitialSubscribersContract;

class SetInitialSubscribersNoOp implements SetInitialSubscribersContract
{
    public function set(Issue $issue): Issue
    {
        return $issue;
    }
}
