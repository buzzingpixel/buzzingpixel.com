<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Replies\Contracts\EditReplyContract;
use App\Http\Response\Support\Replies\EditReply\EditReply;
use App\Http\Response\Support\Replies\EditReply\EditReplyInvalid;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;

class EditReplyFactory
{
    public function __construct(
        private EditReply $editReply,
        private EditReplyInvalid $invalid,
    ) {
    }

    public function getEditReply(
        GetReplyResults $results,
        LoggedInUser $loggedInUser,
        IssueReplyFormValues $formValues,
    ): EditReplyContract {
        if (
            $loggedInUser->hasNoUser() ||
            $results->hasNoReply() ||
            $formValues->isNotValid()
        ) {
            return $this->invalid;
        }

        $user = $loggedInUser->user();

        $replyUserId = $results->reply()->userGuarantee()->id();

        if ($user->isAdmin() || $user->id() === $replyUserId) {
            return $this->editReply;
        }

        return $this->invalid;
    }
}
