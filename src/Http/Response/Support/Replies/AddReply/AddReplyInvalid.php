<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\AddReply;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Replies\Contracts\AddReplyContract;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;

class AddReplyInvalid implements AddReplyContract
{
    public function add(
        GetIssueResults $results,
        LoggedInUser $loggedInUser,
        IssueReplyFormValues $formValues,
    ): Payload {
        return new Payload(
            status: Payload::STATUS_NOT_VALID,
            result: [
                'message' => 'Unable to add reply',
                'formValues' => $formValues,
            ],
        );
    }
}
