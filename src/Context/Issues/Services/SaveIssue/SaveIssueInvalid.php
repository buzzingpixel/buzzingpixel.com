<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssue\Contracts\SaveIssueContract;
use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Payload\Payload;
use App\Persistence\Entities\Support\IssueRecord;

class SaveIssueInvalid implements SaveIssueContract
{
    public function save(
        Issue $issue,
        ?IssueRecord $record,
        ValidityContract $validity,
    ): Payload {
        return new Payload(
            $validity->payloadStatus(),
            [
                'message' => 'Unable to save issue',
                'errors' => $validity->validationErrors(),
            ]
        );
    }
}
