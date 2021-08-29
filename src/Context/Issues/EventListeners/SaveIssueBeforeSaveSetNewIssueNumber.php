<?php

declare(strict_types=1);

namespace App\Context\Issues\EventListeners;

use App\Context\Issues\Events\SaveIssueBeforeValidate;
use App\Context\Issues\Services\IssueOnBeforeValidateSetIssueNumber\Factories\SetNewIssueNumberFactory;

class SaveIssueBeforeSaveSetNewIssueNumber
{
    public function __construct(
        private SetNewIssueNumberFactory $setNewIssueNumberFactory,
    ) {
    }

    public function onBeforeValidate(
        SaveIssueBeforeValidate $beforeValidate
    ): void {
        $beforeValidate->issue = $this->setNewIssueNumberFactory
            ->getSetNewIssueNumber(beforeValidate: $beforeValidate)
            ->setIssueNumber(issue: $beforeValidate->issue);
    }
}
