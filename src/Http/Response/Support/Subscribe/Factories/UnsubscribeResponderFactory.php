<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Factories;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\UnsubscribeResponderContract;
use App\Http\Response\Support\Subscribe\Responders\UnsubscribeResponder;
use App\Http\Response\Support\Subscribe\Responders\UnsubscribeResponderInvalid;

class UnsubscribeResponderFactory
{
    public function __construct(
        private UnsubscribeResponder $responder,
        private UnsubscribeResponderInvalid $responderInvalid,
    ) {
    }

    public function getResponder(
        GetIssueResults $results,
    ): UnsubscribeResponderContract {
        if ($results->hasNoIssue()) {
            return $this->responderInvalid;
        }

        return $this->responder;
    }
}
