<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Contracts;

use App\Context\Issues\Entities\Issue;

interface SetInitialSubscribersContract
{
    public function set(Issue $issue): Issue;
}
