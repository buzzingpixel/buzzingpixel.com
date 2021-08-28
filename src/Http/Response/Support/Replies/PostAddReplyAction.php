<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Factories\GetIssueFactory;
use App\Http\Response\Support\Replies\Entities\IssueReplyFormValues;
use App\Http\Response\Support\Replies\Factories\AddReplyFactory;
use App\Http\Response\Support\Replies\Factories\PostAddReplyResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostAddReplyAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private AddReplyFactory $addReplyFactory,
        private PostAddReplyResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $results = $this->getIssueFactory->getIssue(
            issueNumber: $issueNumber,
            user: $this->loggedInUser->user(),
        );

        $post = $request->getParsedBody();

        assert(is_array($post));

        $formValues = IssueReplyFormValues::fromPostArray(post: $post);

        $payload = $this->addReplyFactory->getAddReply(
            results: $results,
            formValues:  $formValues,
            loggedInUser: $this->loggedInUser,
        )->add(
            results: $results,
            formValues: $formValues,
            loggedInUser: $this->loggedInUser,
        );

        return $this->responderFactory->getResponder(
            payload: $payload,
        )->respond(
            payload: $payload,
            issueNumber: $issueNumber,
        );
    }
}
