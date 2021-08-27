<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue;

use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\EditIssue\Factories\PostIssueEditResponderFactory;
use App\Http\Response\Support\EditIssue\Factories\SaveIssueEditsFactory;
use App\Http\Response\Support\Entities\IssueFormValues;
use App\Http\Response\Support\Factories\GetIssueFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostEditIssueAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private LoggedInUser $loggedInUser,
        private GetIssueFactory $getIssueFactory,
        private SaveIssueEditsFactory $saveIssueEditsFactory,
        private PostIssueEditResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $issueNumber = (int) $request->getAttribute('issueNumber');

        $getIssueResults = $this->getIssueFactory->getIssue(
            issueNumber: $issueNumber,
            user: $this->loggedInUser->user(),
        );

        $post = $request->getParsedBody();

        assert(is_array($post));

        $post['message'] = 'unused';

        $formValues = IssueFormValues::fromPostArray(
            post: $post,
            softwareApi: $this->softwareApi,
        );

        $payload = $this->saveIssueEditsFactory->getSaveIssueEdits(
            formValues: $formValues,
            getIssueResults: $getIssueResults,
            loggedInUser: $this->loggedInUser,
        )->save(
            formValues: $formValues,
            getIssueResults: $getIssueResults,
        );

        return $this->responderFactory->getResponder(
            payload: $payload,
        )->respond(
            payload: $payload,
            issueNumber: $issueNumber,
        );
    }
}
