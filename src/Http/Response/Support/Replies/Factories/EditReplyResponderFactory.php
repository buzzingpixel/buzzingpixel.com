<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Replies\Contracts\EditReplyResponderContract;
use App\Http\Response\Support\Replies\Responders\EditReplyResponderInvalid;
use App\Http\Response\Support\Replies\Responders\EditReplyResponderValid;
use Psr\Http\Message\ServerRequestInterface;

class EditReplyResponderFactory
{
    public function __construct(private EditReplyResponderValid $valid)
    {
    }

    public function getResponder(
        GetReplyResults $results,
        LoggedInUser $loggedInUser,
        ServerRequestInterface $request,
    ): EditReplyResponderContract {
        if ($loggedInUser->hasNoUser() || $results->hasNoReply()) {
            return new EditReplyResponderInvalid($request);
        }

        $user = $loggedInUser->user();

        $replyUserId = $results->reply()->userGuarantee()->id();

        if ($user->isAdmin() || $user->id() === $replyUserId) {
            return $this->valid;
        }

        return new EditReplyResponderInvalid($request);
    }
}
