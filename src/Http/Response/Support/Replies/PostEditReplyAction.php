<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Factories\GetIssueFactory;
use App\Http\Response\Support\Factories\ReplyFromIssueResultsFactory;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Http\Response\Support\Replies\Factories\EditReplyFactory;
use App\Http\Response\Support\Replies\Factories\PostEditReplyResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostEditReplyAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private EditReplyFactory $editReplyFactory,
        private PostEditReplyResponderFactory $responderFactory,
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

        $post = $request->getParsedBody();

        assert(is_array($post));

        $formValues = IssueReplyFormValues::fromPostArray(post: $post);

        $payload = $this->editReplyFactory->getEditReply(
            results: $results,
            formValues: $formValues,
            loggedInUser: $this->loggedInUser,
        )->edit(
            results: $results,
            formValues: $formValues,
        );

        return $this->responderFactory->getResponder(
            payload: $payload,
        )->respond(
            payload: $payload,
            issueNumber: $issueNumber,
            replyId: $replyId,
        );
    }
}
