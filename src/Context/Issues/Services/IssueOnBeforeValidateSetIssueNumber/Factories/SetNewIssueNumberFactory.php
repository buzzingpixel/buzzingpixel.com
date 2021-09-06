<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Factories;

use App\Context\Issues\Events\SaveIssueBeforeValidate;
use App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Contracts\SetNewIssueNumberContract;
use App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Services\SetNewIssueNumber;
use App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Services\SetNewIssueNumberNoOp;

class SetNewIssueNumberFactory
{
    public function __construct(
        private SetNewIssueNumber $setNewIssueNumber,
        private SetNewIssueNumberNoOp $setNewIssueNumberNoOp,
    ) {
    }

    public function getSetNewIssueNumber(
        SaveIssueBeforeValidate $beforeValidate
    ): SetNewIssueNumberContract {
        if ($beforeValidate->isNew && $beforeValidate->setNewIssueNumber) {
            return $this->setNewIssueNumber;
        }

        return $this->setNewIssueNumberNoOp;
    }
}
