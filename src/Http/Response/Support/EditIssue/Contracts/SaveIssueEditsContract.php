<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Contracts;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Entities\IssueFormValues;
use App\Payload\Payload;

interface SaveIssueEditsContract
{
    public function save(
        IssueFormValues $formValues,
        GetIssueResults $getIssueResults,
    ): Payload;
}
