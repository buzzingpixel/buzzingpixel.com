<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Contracts;

use App\Http\Response\Support\Entities\IssueFormValues;
use App\Payload\Payload;

interface CreateNewIssueContract
{
    public function createNewIssue(IssueFormValues $formValues): Payload;
}
