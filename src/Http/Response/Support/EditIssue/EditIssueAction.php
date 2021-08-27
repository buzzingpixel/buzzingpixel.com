<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\EditIssue\Factories\IssueEditResponderFactory;
use App\Http\Response\Support\Factories\GetIssueFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EditIssueAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private IssueEditResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $issueResult = $this->getIssueFactory->getIssue(
            issueNumber: $issueNumber,
            user: $this->loggedInUser->user(),
        );

        return $this->responderFactory->getResponder(
            request: $request,
            getIssueResults: $issueResult,
            loggedInUser: $this->loggedInUser,
        )->respond(getIssueResults: $issueResult);
    }
}
