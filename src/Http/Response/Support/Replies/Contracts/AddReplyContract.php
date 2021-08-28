<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Contracts;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;

interface AddReplyContract
{
    public function add(
        GetIssueResults $results,
        LoggedInUser $loggedInUser,
        IssueReplyFormValues $formValues,
    ): Payload;
}
