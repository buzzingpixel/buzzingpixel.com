<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Responders;

use App\Http\Entities\Meta;
use App\Http\Response\Support\Entities\GetReplyResults;
use App\Http\Response\Support\Factories\IssueMessageFactory;
use App\Http\Response\Support\Replies\Contracts\EditReplyResponderContract;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

use function assert;
use function is_array;

class EditReplyResponderValid implements EditReplyResponderContract
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private IssueMessageFactory $issueMessageFactory,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(GetReplyResults $results): ResponseInterface
    {
        $message = $this->issueMessageFactory->getIssueMessage();

        $inputValues = $message['inputValues'] ?? [];

        assert(is_array($inputValues));

        $errorMessages = $message['errorMessages'] ?? [];

        assert(is_array($errorMessages));

        $response = $this->responseFactory->createResponse();

        $reply = $results->reply();

        $issue = $reply->issue();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/Replies/Templates/EditReply.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Edit reply',
                ),
                'breadcrumbSingle' => [
                    'content' => 'Issue',
                    'uri' => '/support/issue/' . $issue->issueNumber(),
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Home',
                        'uri' => '/',
                    ],
                    [
                        'content' => 'Support Dashboard',
                        'uri' => '/support',
                    ],
                    [
                        'content' => 'Issue',
                        'uri' => '/support/issue/' . $issue->issueNumber(),
                    ],
                    ['content' => 'Edit reply'],
                ],
                'supportMenu' => $this->config->supportMenu(),
                'issueMessage' => $message,
                'headline' => 'Edit reply for: ' . $issue->shortDescription(),
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Submit edits',
                    'formAction' => '/support/issue/' .
                        $issue->issueNumber() .
                        '/edit-reply/' .
                        $reply->id(),
                    'inputs' => [
                        [
                            'template' => 'TextArea',
                            'limitedWidth' => false,
                            'label' => 'Comment',
                            'labelSmall' => '(required)',
                            'subHeading' => 'use Markdown for formatting',
                            'name' => 'comment',
                            'attrs' => ['rows' => 16],
                            'value' => (string) ($inputValues['comment'] ?? $reply->message()),
                            'errorMessage' => (string) ($errorMessages['message'] ?? ''),
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}
