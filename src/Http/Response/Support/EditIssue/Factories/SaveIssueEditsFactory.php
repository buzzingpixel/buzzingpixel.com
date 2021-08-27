<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\EditIssue\Contracts\SaveIssueEditsContract;
use App\Http\Response\Support\EditIssue\SaveIssueEdits\SaveIssueEditsAdmin;
use App\Http\Response\Support\EditIssue\SaveIssueEdits\SaveIssueEditsInvalid;
use App\Http\Response\Support\EditIssue\SaveIssueEdits\SaveIssueEditsUser;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Entities\IssueFormValues;

class SaveIssueEditsFactory
{
    public function __construct(
        private SaveIssueEditsUser $user,
        private SaveIssueEditsAdmin $admin,
        private SaveIssueEditsInvalid $invalid,
    ) {
    }

    public function getSaveIssueEdits(
        LoggedInUser $loggedInUser,
        IssueFormValues $formValues,
        GetIssueResults $getIssueResults,
    ): SaveIssueEditsContract {
        if (
            $loggedInUser->hasNoUser() ||
            $getIssueResults->hasNoIssue() ||
            $formValues->isNotValid()
        ) {
            return $this->invalid;
        }

        if ($loggedInUser->user()->isAdmin()) {
            return $this->admin;
        }

        $issueUserId = $getIssueResults->issue()->userGuarantee()->id();

        if ($loggedInUser->user()->id() === $issueUserId) {
            return $this->user;
        }

        return $this->invalid;
    }
}
