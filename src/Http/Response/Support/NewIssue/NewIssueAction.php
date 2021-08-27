<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue;

use App\Http\Entities\Meta;
use App\Http\Response\Support\Factories\IssueInputConfigFactory;
use App\Http\Response\Support\Factories\IssueMessageFactory;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NewIssueAction
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
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $message = $this->issueMessageFactory->getIssueMessage();

        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $type = ($queryParams['type'] ?? 'public');

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Support/NewIssue/NewIssue.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Create new issue',
                ),
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
                    ['content' => 'Create Issue'],
                ],
                'supportMenu' => $this->config->supportMenu(),
                'issueMessage' => $message,
                'headline' => 'Create a new issue',
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Open new issue',
                    'formAction' => '/support/new-issue',
                    'inputs' => $this->inputConfigFactory->getInputConfig(
                        type: $type,
                        message: $message,
                    ),
                ],
            ],
        ));

        return $response;
    }
}
