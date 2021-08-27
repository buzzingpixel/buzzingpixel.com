<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Responders;

use App\Http\Response\Support\EditIssue\Contracts\PostIssueEditResponderContract;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostIssueEditResponderValid implements PostIssueEditResponderContract
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
                'message' => 'Issue edited!',
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                '/support/issue/' . $issueNumber,
            );
    }
}
