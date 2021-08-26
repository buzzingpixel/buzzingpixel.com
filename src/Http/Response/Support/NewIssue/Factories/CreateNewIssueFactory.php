<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Factories;

use App\Http\Response\Support\NewIssue\Contracts\CreateNewIssueContract;
use App\Http\Response\Support\NewIssue\CreateNewIssue\CreateNewIssue;
use App\Http\Response\Support\NewIssue\CreateNewIssue\CreateNewIssueInvalid;
use App\Http\Response\Support\NewIssue\Entities\FormValues;

class CreateNewIssueFactory
{
    public function __construct(
        private CreateNewIssue $createNewIssue,
        private CreateNewIssueInvalid $createNewIssueInvalid,
    ) {
    }

    public function getCreateNewIssue(
        FormValues $formValues,
    ): CreateNewIssueContract {
        if ($formValues->isNotValid()) {
            return $this->createNewIssueInvalid;
        }

        return $this->createNewIssue;
    }
}
