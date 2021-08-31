<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Factories;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\SubscribeResponderContract;
use App\Http\Response\Support\Subscribe\Responders\SubscribeResponder;
use App\Http\Response\Support\Subscribe\Responders\SubscribeResponderInvalid;

class SubscribeResponderFactory
{
    public function __construct(
        private SubscribeResponder $responder,
        private SubscribeResponderInvalid $responderInvalid,
    ) {
    }

    public function getResponder(
        GetIssueResults $results,
    ): SubscribeResponderContract {
        if ($results->hasNoIssue()) {
            return $this->responderInvalid;
        }

        return $this->responder;
    }
}
