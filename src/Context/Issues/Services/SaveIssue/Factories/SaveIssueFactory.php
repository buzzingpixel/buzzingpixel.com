<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Factories;

use App\Context\Issues\Services\SaveIssue\Contracts\SaveIssueContract;
use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Context\Issues\Services\SaveIssue\SaveIssueExisting;
use App\Context\Issues\Services\SaveIssue\SaveIssueInvalid;
use App\Context\Issues\Services\SaveIssue\SaveIssueNew;
use App\Persistence\Entities\Support\IssueRecord;

class SaveIssueFactory
{
    public function __construct(
        private SaveIssueNew $saveIssueNew,
        private SaveIssueInvalid $saveIssueInvalid,
        private SaveIssueExisting $saveIssueExisting,
    ) {
    }

    public function getSaveIssue(
        ?IssueRecord $record,
        ValidityContract $validity,
    ): SaveIssueContract {
        if ($validity->isInvalid()) {
            return $this->saveIssueInvalid;
        }

        if ($record === null) {
            return $this->saveIssueNew;
        }

        return $this->saveIssueExisting;
    }
}
