<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\CreateNewIssue;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueMessage;
use App\Context\Issues\IssuesApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\IssueFormValues;
use App\Http\Response\Support\NewIssue\Contracts\CreateNewIssueContract;
use App\Payload\Payload;

class CreateNewIssue implements CreateNewIssueContract
{
    public function __construct(
        private IssuesApi $issuesApi,
        private LoggedInUser $loggedInUser,
    ) {
    }

    public function createNewIssue(IssueFormValues $formValues): Payload
    {
        return $this->issuesApi->saveIssue(issue: (new Issue(
            shortDescription: $formValues->shortDescription()->toString(),
            status: Issue::STATUS_NEW,
            isPublic: $formValues->isPublic()->value(),
            softwareVersion: $formValues->softwareVersion()->toString(),
            cmsVersion: $formValues->cmsVersion()->toString(),
            phpVersion: $formValues->phpVersion()->toString(),
            mySqlVersion: $formValues->mySqlVersion()->toString(),
            additionalEnvDetails: $formValues->additionalEnvDetails()->toString(),
            privateInfo: $formValues->privateInfo()->toString(),
            user: $this->loggedInUser->user(),
            software: $formValues->software()->getSoftware(),
        ))->withAddedIssueMessage(
            newIssueMessage: new IssueMessage(
                message: $formValues->message()->toString(),
                user: $this->loggedInUser->user(),
            ),
        ));
    }
}
