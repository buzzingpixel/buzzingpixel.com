<?php

declare(strict_types=1);

namespace App\Http\Response\Support\IssueDisplay\Responders;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Factories\IssueMessageFactory;
use App\Http\Response\Support\IssueDisplay\Contracts\IssueDisplayResponderContract;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class IssueDisplayResponder implements IssueDisplayResponderContract
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private IssueMessageFactory $issueMessageFactory,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(
        GetIssueResults $getIssueResults,
    ): ResponseInterface {
        $message = $this->issueMessageFactory->getIssueMessage();

        $response = $this->responseFactory->createResponse();

        $issue = $getIssueResults->issue();

        $user = $this->loggedInUser->userOrNull();

        $allMessages = $getIssueResults->issue()->issueMessages();

        $issueDescription = $allMessages->first();

        $issueReplies = $allMessages->slice(1);

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/IssueDisplay/Templates/IssueDisplay.twig',
            [
                'meta' => new Meta(
                    metaTitle: $issue->shortDescription() . ' | Support',
                ),
                'maxWidth' => '8xl',
                'breadcrumbSingle' => [
                    'content' => 'Support Dashboard',
                    'uri' => '/support',
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
                    ['content' => 'View Issue'],
                ],
                'supportMenu' => $this->config->supportMenu(),
                'issueMessage' => $message,
                'issue' => $issue,
                'issueDescription' => $issueDescription,
                'issueReplies' => $issueReplies,
                'isEditable' => ($user?->isAdmin() ||
                    $user?->id() === $issue->userGuarantee()->id()) &&
                    $issue->isNotDuplicate(),
                'isSubscribable' => $issue->user() !== null,
            ],
        ));

        return $response;
    }
}
