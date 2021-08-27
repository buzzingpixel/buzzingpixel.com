<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueDisplay;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Factories\GetIssueFactory;
use App\Http\Response\Support\IssueDisplay\Factory\IssueDisplayResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IssueDisplayAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private IssueDisplayResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $issueResult = $this->getIssueFactory->getIssue(
            issueNumber: $issueNumber,
            user: $this->loggedInUser->userOrNull(),
        );

        return $this->responderFactory->getResponder(
            request: $request,
            getIssueResults: $issueResult,
        )->respond(getIssueResults: $issueResult);
    }
}
