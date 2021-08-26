<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Factories;

use App\Http\Response\Support\NewIssue\Contracts\PostCreateNewIssueResponderContract;
use App\Http\Response\Support\NewIssue\Responders\PostCreateNewIssueResponderInvalid;
use App\Http\Response\Support\NewIssue\Responders\PostCreateNewIssueResponderValid;
use App\Payload\Payload;

class PostCreateNewIssueResponderFactory
{
    public function __construct(
        private PostCreateNewIssueResponderValid $valid,
        private PostCreateNewIssueResponderInvalid $invalid,
    ) {
    }

    public function getResponder(
        Payload $payload,
    ): PostCreateNewIssueResponderContract {
        if ($payload->getStatus() !== Payload::STATUS_CREATED) {
            return $this->invalid;
        }

        return $this->valid;
    }
}
