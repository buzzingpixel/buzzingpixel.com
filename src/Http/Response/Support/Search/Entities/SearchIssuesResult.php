<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Search\Entities;

use App\Context\Issues\Entities\IssuesResult;

class SearchIssuesResult
{
    public function __construct(
        private bool $isValid,
        private IssuesResult $issuesResult,
    ) {
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isInvalid(): bool
    {
        return ! $this->isValid;
    }

    public function issuesResult(): IssuesResult
    {
        return $this->issuesResult;
    }
}
