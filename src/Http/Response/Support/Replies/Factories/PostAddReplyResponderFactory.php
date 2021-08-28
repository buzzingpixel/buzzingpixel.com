<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Factories;

use App\Http\Response\Support\Replies\Contracts\PostAddReplyResponderContract;
use App\Http\Response\Support\Replies\Responders\PostAddReplyResponderInvalid;
use App\Http\Response\Support\Replies\Responders\PostAddReplyResponderValid;
use App\Payload\Payload;

class PostAddReplyResponderFactory
{
    public function __construct(
        private PostAddReplyResponderValid $valid,
        private PostAddReplyResponderInvalid $invalid,
    ) {
    }

    public function getResponder(
        Payload $payload,
    ): PostAddReplyResponderContract {
        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
            return $this->invalid;
        }

        return $this->valid;
    }
}
