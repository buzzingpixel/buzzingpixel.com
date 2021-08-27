<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\CreateNewIssue;

use App\Http\Response\Support\Entities\IssueFormValues;
use App\Http\Response\Support\NewIssue\Contracts\CreateNewIssueContract;
use App\Payload\Payload;

class CreateNewIssueInvalid implements CreateNewIssueContract
{
    public function createNewIssue(IssueFormValues $formValues): Payload
    {
        return new Payload(
            status: Payload::STATUS_NOT_VALID,
            result: [
                'message' => 'Unable to create a new issue',
                'formValues' => $formValues,
            ],
        );
    }
}
