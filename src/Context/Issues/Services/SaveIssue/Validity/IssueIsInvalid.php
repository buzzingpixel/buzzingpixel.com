<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Validity;

use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Context\Issues\Services\SaveIssue\Entities\ValidityErrorMessageCollection;
use App\Payload\Payload;

class IssueIsInvalid implements ValidityContract
{
    /** @phpstan-ignore-next-line */
    public function __construct(private ValidityErrorMessageCollection $validationErrors)
    {
    }

    public function isValid(): bool
    {
        return false;
    }

    public function isInvalid(): bool
    {
        return true;
    }

    public function payloadStatus(): string
    {
        return Payload::STATUS_NOT_VALID;
    }

    /** @phpstan-ignore-next-line */
    public function validationErrors(): ValidityErrorMessageCollection
    {
        return $this->validationErrors;
    }
}
