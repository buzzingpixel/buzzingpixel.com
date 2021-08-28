<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Responders;

use App\Http\Response\Support\Replies\Contracts\PostAddReplyResponderContract;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostAddReplyResponderValid implements PostAddReplyResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(
        Payload $payload,
        int $issueNumber,
    ): ResponseInterface {
        $this->flashMessages->addMessage(
            'IssueMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'message' => 'Reply added',
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                '/support/issue/' . $issueNumber,
            );
    }
}
