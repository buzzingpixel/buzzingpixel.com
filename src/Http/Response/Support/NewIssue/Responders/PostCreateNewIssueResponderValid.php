<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Responders;

use App\Context\Issues\Entities\Issue;
use App\Http\Response\Support\IssueListing\Services\IssueLinkResolver;
use App\Http\Response\Support\NewIssue\Contracts\PostCreateNewIssueResponderContract;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

use function assert;

class PostCreateNewIssueResponderValid implements PostCreateNewIssueResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private IssueLinkResolver $issueLinkResolver,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(Payload $payload): ResponseInterface
    {
        $issue = $payload->getResult()['issueEntity'];

        assert($issue instanceof Issue);

        $this->flashMessages->addMessage(
            'IssueMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'message' => 'Issue created!',
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                $this->issueLinkResolver->resolveLinkToIssue(
                    issue: $issue,
                ),
            );
    }
}
