<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssue\Contracts;

use App\Context\Issues\Services\SaveIssue\Entities\ValidityErrorMessageCollection;

interface ValidityContract
{
    public function isValid(): bool;

    public function isInvalid(): bool;

    public function payloadStatus(): string;

    /** @phpstan-ignore-next-line */
    public function validationErrors(): ValidityErrorMessageCollection;
}
