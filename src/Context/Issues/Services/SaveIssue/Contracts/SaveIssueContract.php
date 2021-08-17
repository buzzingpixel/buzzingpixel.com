<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Contracts;

use App\Context\Issues\Entities\Issue;
use App\Payload\Payload;
use App\Persistence\Entities\Support\IssueRecord;

interface SaveIssueContract
{
    public function save(
        Issue $issue,
        ?IssueRecord $record,
        ValidityContract $validity,
    ): Payload;
}
