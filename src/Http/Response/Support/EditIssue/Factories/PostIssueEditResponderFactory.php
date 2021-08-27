<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Factories;

use App\Http\Response\Support\EditIssue\Contracts\PostIssueEditResponderContract;
use App\Http\Response\Support\EditIssue\Responders\PostIssueEditResponderInvalid;
use App\Http\Response\Support\EditIssue\Responders\PostIssueEditResponderValid;
use App\Payload\Payload;

class PostIssueEditResponderFactory
{
    public function __construct(
        private PostIssueEditResponderValid $valid,
        private PostIssueEditResponderInvalid $invalid,
    ) {
    }

    public function getResponder(
        Payload $payload,
    ): PostIssueEditResponderContract {
        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
            return $this->invalid;
        }

        return $this->valid;
    }
}
