<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\SaveIssueEdits;

use App\Context\Issues\IssuesApi;
use App\Http\Response\Support\EditIssue\Contracts\SaveIssueEditsContract;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Entities\IssueFormValues;
use App\Payload\Payload;

class SaveIssueEditsUser implements SaveIssueEditsContract
{
    public function __construct(private IssuesApi $issuesApi)
    {
    }

    public function save(
        IssueFormValues $formValues,
        GetIssueResults $getIssueResults,
    ): Payload {
        return $this->issuesApi->saveIssue(
            issue: $getIssueResults->issue()
                ->withShortDescription(
                    shortDescription: $formValues->shortDescription()
                        ->toString(),
                )
                ->withIsPublic(isPublic: $formValues->isPublic()->value())
                ->withSoftwareVersion(
                    softwareVersion: $formValues->softwareVersion()->toString(),
                )
                ->withCmsVersion(
                    cmsVersion: $formValues->cmsVersion()->toString()
                )
                ->withPhpVersion(
                    phpVersion: $formValues->phpVersion()->toString(),
                )
                ->withMySqlVersion(
                    mySqlVersion: $formValues->mySqlVersion()->toString(),
                )
                ->withAdditionalEnvDetails(
                    additionalEnvDetails: $formValues->additionalEnvDetails()
                        ->toString(),
                )
                ->withPrivateInfo(
                    $formValues->privateInfo()->toString(),
                )
                ->withSoftware(
                    software: $formValues->software()->getSoftware(),
                ),
        );
    }
}
