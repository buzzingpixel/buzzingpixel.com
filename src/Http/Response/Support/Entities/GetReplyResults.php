<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Entities;

use App\Context\Issues\Entities\IssueMessage;

use function assert;

class GetReplyResults
{
    public function __construct(
        private ?IssueMessage $reply,
    ) {
    }

    public function reply(): IssueMessage
    {
        $reply = $this->reply;

        assert($reply instanceof IssueMessage);

        return $reply;
    }

    public function hasReply(): bool
    {
        return $this->reply !== null;
    }

    public function hasNoReply(): bool
    {
        return $this->reply === null;
    }
}
