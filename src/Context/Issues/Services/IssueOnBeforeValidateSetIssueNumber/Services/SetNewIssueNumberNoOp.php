<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Contracts\SetNewIssueNumberContract;

class SetNewIssueNumberNoOp implements SetNewIssueNumberContract
{
    public function setIssueNumber(Issue $issue): Issue
    {
        return $issue;
    }
}
