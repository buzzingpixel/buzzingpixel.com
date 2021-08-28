<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Factories;

use App\Http\Response\Support\Replies\Contracts\PostEditReplyResponderContract;
use App\Http\Response\Support\Replies\Responders\PostEditReplyResponderInvalid;
use App\Http\Response\Support\Replies\Responders\PostEditReplyResponderValid;
use App\Payload\Payload;

class PostEditReplyResponderFactory
{
    public function __construct(
        private PostEditReplyResponderValid $valid,
        private PostEditReplyResponderInvalid $invalid,
    ) {
    }

    public function getResponder(
        Payload $payload,
    ): PostEditReplyResponderContract {
        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
            return $this->invalid;
        }

        return $this->valid;
    }
}
