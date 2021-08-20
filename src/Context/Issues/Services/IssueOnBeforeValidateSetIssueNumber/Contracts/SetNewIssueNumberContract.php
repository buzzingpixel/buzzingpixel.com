<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Contracts;

use App\Context\Issues\Entities\Issue;

interface SetNewIssueNumberContract
{
    public function setIssueNumber(Issue $issue): Issue;
}
