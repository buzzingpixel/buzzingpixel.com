<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue;

use App\Context\Software\SoftwareApi;
use App\Http\Response\Support\Entities\IssueFormValues;
use App\Http\Response\Support\NewIssue\Factories\CreateNewIssueFactory;
use App\Http\Response\Support\NewIssue\Factories\PostCreateNewIssueResponderFactory;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostNewIssueAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private CreateNewIssueFactory $createNewIssueFactory,
        private PostCreateNewIssueResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        throw new Exception('Posting has have been disabled');

        $post = $request->getParsedBody();

        assert(is_array($post));

        $formValues = IssueFormValues::fromPostArray(
            post: $post,
            softwareApi: $this->softwareApi,
        );

        $payload = $this->createNewIssueFactory->getCreateNewIssue(
            formValues: $formValues,
        )->createNewIssue(formValues: $formValues);

        return $this->responderFactory->getResponder(
            payload: $payload,
        )->respond(payload: $payload);
    }
}
