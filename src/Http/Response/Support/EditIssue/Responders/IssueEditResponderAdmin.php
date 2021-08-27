<?php

declare(strict_types=1);

namespace App\Http\Response\Support\EditIssue\Responders;

use App\Http\Entities\Meta;
use App\Http\Response\Support\EditIssue\Contracts\IssueEditResponderContract;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Factories\IssueInputConfigFactory;
use App\Http\Response\Support\Factories\IssueMessageFactory;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IssueEditResponderAdmin implements IssueEditResponderContract
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private IssueMessageFactory $issueMessageFactory,
        private ResponseFactoryInterface $responseFactory,
        private IssueInputConfigFactory $inputConfigFactory,
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function respond(
        GetIssueResults $getIssueResults,
    ): ResponseInterface {
        $message = $this->issueMessageFactory->getIssueMessage();

        $response = $this->responseFactory->createResponse();

        $issue = $getIssueResults->issue();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/Templates/EditIssue.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Admin edit issue',
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
                    ['content' => 'Admin edit issue'],
                ],
                'supportMenu' => $this->config->supportMenu(),
                'issueMessage' => $message,
                'headline' => 'Edit issue: ' . $issue->shortDescription(),
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Submit edits',
                    'formAction' => '/support/issue/' .
                        $issue->issueNumber() .
                        '/edit',
                    'inputs' => $this->inputConfigFactory->getAdminInputConfig(
                        message: $message,
                        issue: $issue,
                    ),
                ],
            ],
        ));

        return $response;
    }
}
