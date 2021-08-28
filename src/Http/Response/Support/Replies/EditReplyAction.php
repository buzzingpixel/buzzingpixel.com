<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Factories\GetIssueFactory;
use App\Http\Response\Support\Factories\ReplyFromIssueResultsFactory;
use App\Http\Response\Support\Replies\Factories\EditReplyResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EditReplyAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private EditReplyResponderFactory $responderFactory,
        private ReplyFromIssueResultsFactory $replyFromIssueResultsFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $replyId = (string) $request->getAttribute('replyId');

        $results = $this->replyFromIssueResultsFactory->getReply(
            results: $this->getIssueFactory->getIssue(
                issueNumber: $issueNumber,
                user: $this->loggedInUser->user(),
            ),
            replyId: $replyId,
        );

        return $this->responderFactory->getResponder(
            results: $results,
            loggedInUser: $this->loggedInUser,
            request: $request,
        )->respond(
            results: $results,
        );
    }
}
