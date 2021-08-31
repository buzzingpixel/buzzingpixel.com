<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Responders;

use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\UnsubscribeResponderContract;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages as FlashMessages;

class UnsubscribeResponder implements UnsubscribeResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(
        int $issueNumber,
        GetIssueResults $results,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $this->flashMessages->addMessage(
            'IssueMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'message' => 'You have been unsubscribed',
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                '/support/issue/' . $issueNumber,
            );
    }
}
