<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Factories;

use Slim\Flash\Messages as FlashMessages;

use function assert;
use function is_array;

class IssueMessageFactory
{
    public function __construct(
        private FlashMessages $flashMessages,
    ) {
    }

    /**
     * @return mixed[]|null
     */
    public function getIssueMessage(): ?array
    {
        $message = $this->flashMessages->getMessage('IssueMessage');

        assert($message === null || is_array($message));

        if ($message === null) {
            return null;
        }

        /** @psalm-suppress MixedAssignment */
        $message = $message[0];

        assert(is_array($message));

        return $message;
    }
}
