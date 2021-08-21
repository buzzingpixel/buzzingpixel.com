<?php

declare(strict_types=1);

namespace App\Context\Issues\Entities;

class IssuesResult
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private int $absoluteTotal,
        /** @phpstan-ignore-next-line */
        private IssueCollection $issueCollection,
    ) {
    }

    public function absoluteTotal(): int
    {
        return $this->absoluteTotal;
    }

    /** @phpstan-ignore-next-line */
    public function issueCollection(): IssueCollection
    {
        return $this->issueCollection;
    }
}
