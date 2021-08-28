<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Contracts;

use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;

interface EditReplyContract
{
    public function edit(
        GetReplyResults $results,
        IssueReplyFormValues $formValues,
    ): Payload;
}
