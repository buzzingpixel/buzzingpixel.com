<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\SaveIssueEdits;

use App\Http\Response\Support\EditIssue\Contracts\SaveIssueEditsContract;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Entities\IssueFormValues;
use App\Payload\Payload;

class SaveIssueEditsInvalid implements SaveIssueEditsContract
{
    public function save(
        IssueFormValues $formValues,
        GetIssueResults $getIssueResults,
    ): Payload {
        return new Payload(
            status: Payload::STATUS_NOT_VALID,
            result: [
                'message' => 'Unable to save issue edits',
                'formValues' => $formValues,
            ],
        );
    }
}
