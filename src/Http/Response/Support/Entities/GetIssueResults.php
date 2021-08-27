<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Entities;

use App\Context\Issues\Entities\Issue;

use function assert;

class GetIssueResults
{
    public function __construct(private ?Issue $issue)
    {
    }

    public function issue(): Issue
    {
        $issue = $this->issue;

        assert($issue !== null);

        return $issue;
    }

    public function hasIssue(): bool
    {
        return $this->issue !== null;
    }

    public function hasNoIssue(): bool
    {
        return $this->issue === null;
    }
}
