<?php

declare(strict_types=1);

namespace App\Context\Issues;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssue;
use App\Payload\Payload;

class IssuesApi
{
    public function __construct(
        private SaveIssue $saveIssue,
    ) {
    }

    public function saveIssue(Issue $issue): Payload
    {
        return $this->saveIssue->save($issue);
    }
}
