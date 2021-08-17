<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait IssueNumber
{
    private int $issueNumber;

    public function issueNumber(): int
    {
        return $this->issueNumber;
    }

    /**
     * @return $this
     */
    public function withIssueNumber(int $issueNumber): self
    {
        $clone = clone $this;

        $clone->issueNumber = $issueNumber;

        return $clone;
    }
}
