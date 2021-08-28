<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Replies\AddReply\AddReply;
use App\Http\Response\Support\Replies\AddReply\AddReplyInvalid;
use App\Http\Response\Support\Replies\Contracts\AddReplyContract;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;

class AddReplyFactory
{
    public function __construct(
        private AddReply $addReply,
        private AddReplyInvalid $invalid,
    ) {
    }

    public function getAddReply(
        LoggedInUser $loggedInUser,
        IssueReplyFormValues $formValues,
        GetIssueResults $results,
    ): AddReplyContract {
        if (
            $loggedInUser->hasNoUser() ||
            $results->hasNoIssue() ||
            $formValues->isNotValid()
        ) {
            return $this->invalid;
        }

        $issueUserId = $results->issue()->userGuarantee()->id();

        if ($results->issue()->isPublic()) {
            return $this->addReply;
        }

        if (
            $loggedInUser->user()->isAdmin() ||
            $loggedInUser->user()->id() === $issueUserId
        ) {
            return $this->addReply;
        }

        return $this->invalid;
    }
}
