<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait IssueNumber
{
    /**
     * @Mapping\Column(
     *     name="issue_number",
     *     type="integer",
     *     options={"unsigned"=true},
     * )
     */
    protected int $issueNumber = 1;

    public function getIssueNumber(): int
    {
        return $this->issueNumber;
    }

    public function setIssueNumber(int $issueNumber): void
    {
        $this->issueNumber = $issueNumber;
    }
}
