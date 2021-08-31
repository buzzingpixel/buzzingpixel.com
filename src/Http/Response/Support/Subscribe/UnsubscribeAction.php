<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Factories\GetIssueFactory;
use App\Http\Response\Support\Subscribe\Factories\UnsubscribeFactory;
use App\Http\Response\Support\Subscribe\Factories\UnsubscribeResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UnsubscribeAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private UnsubscribeFactory $unsubscribeFactory,
        private UnsubscribeResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $results = $this->getIssueFactory->getIssue(
            issueNumber: $issueNumber,
            user: $this->loggedInUser->user(),
        );

        $this->unsubscribeFactory->getUnsubscribe(
            results: $results,
            user: $this->loggedInUser->user(),
        )->unsubscribeUser(
            results: $results,
            user: $this->loggedInUser->user(),
        );

        return $this->responderFactory->getResponder(
            results: $results,
        )->respond(
            request: $request,
            results: $results,
            issueNumber: $issueNumber,
        );
    }
}
