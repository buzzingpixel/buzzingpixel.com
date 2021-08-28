<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Responders;

use App\Http\Response\Support\Replies\Contracts\PostEditReplyResponderContract;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

use function assert;

class PostEditReplyResponderInvalid implements PostEditReplyResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(
        Payload $payload,
        int $issueNumber,
        string $replyId,
    ): ResponseInterface {
        $formValues = $payload->getResult()['formValues'];

        assert($formValues instanceof IssueReplyFormValues);

        $this->flashMessages->addMessage(
            'IssueMessage',
            [
                'status' => $payload->getStatus(),
                'message' => (string) $payload->getResult()['message'],
                'inputValues' => $formValues->valuesForHtml(),
                'errorMessages' => $formValues->errorMessages(),
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                '/support/issue/' . $issueNumber . '/edit-reply/' . $replyId,
            );
    }
}
