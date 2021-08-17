<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Validity;

use App\Context\Issues\Services\SaveIssue\Contracts\ValidityContract;
use App\Context\Issues\Services\SaveIssue\Entities\ValidityErrorMessageCollection;
use App\Payload\Payload;

class IssueIsValid implements ValidityContract
{
    /** @phpstan-ignore-next-line */
    private ValidityErrorMessageCollection $validationErrors;

    public function __construct()
    {
        $this->validationErrors = new ValidityErrorMessageCollection();
    }

    public function isValid(): bool
    {
        return true;
    }

    public function isInvalid(): bool
    {
        return false;
    }

    public function payloadStatus(): string
    {
        return Payload::STATUS_VALID;
    }

    /** @phpstan-ignore-next-line */
    public function validationErrors(): ValidityErrorMessageCollection
    {
        return $this->validationErrors;
    }
}
