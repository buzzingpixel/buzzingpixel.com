<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\EditReply;

use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Replies\Contracts\EditReplyContract;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;

class EditReplyInvalid implements EditReplyContract
{
    public function edit(
        GetReplyResults $results,
        IssueReplyFormValues $formValues,
    ): Payload {
        return new Payload(
            status: Payload::STATUS_NOT_VALID,
            result: [
                'message' => 'Unable to edit reply',
                'formValues' => $formValues,
            ],
        );
    }
}
