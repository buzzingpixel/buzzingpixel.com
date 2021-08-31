<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Factories\GetIssueFactory;
use App\Http\Response\Support\Subscribe\Factories\SubscribeFactory;
use App\Http\Response\Support\Subscribe\Factories\SubscribeResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SubscribeAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private SubscribeFactory $subscribeFactory,
        private SubscribeResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $results = $this->getIssueFactory->getIssue(
            issueNumber: $issueNumber,
            user: $this->loggedInUser->user(),
        );

        $this->subscribeFactory->getSubscribe(
            results: $results,
            user: $this->loggedInUser->user(),
        )->subscribeUser(
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
