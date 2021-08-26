<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Responders;

use App\Http\Response\Support\NewIssue\Contracts\PostCreateNewIssueResponderContract;
use App\Http\Response\Support\NewIssue\Entities\FormValues;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;
use Slim\Interfaces\RouteParserInterface;

use function assert;

class PostCreateNewIssueResponderInvalid implements PostCreateNewIssueResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private RouteParserInterface $routeParser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(Payload $payload): ResponseInterface
    {
        $formValues = $payload->getResult()['formValues'];

        assert($formValues instanceof FormValues);

        $this->flashMessages->addMessage(
            'IssueMessage',
            [
                'status' => $payload->getStatus(),
                'message' => (string) $payload->getResult()['message'],
                'inputValues' => $formValues->valuesForHtml(),
                'errorMessages' => $formValues->errorMessages(),
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                $this->routeParser->urlFor(
                    'CreateNewIssue',
                ),
            );
    }
}
