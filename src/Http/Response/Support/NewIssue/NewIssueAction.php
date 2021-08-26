<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue;

use App\Http\Entities\Meta;
use App\Http\Response\Support\NewIssue\Factories\IssueInputConfigFactory;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages as FlashMessages;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function assert;
use function is_array;

class NewIssueAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private FlashMessages $flashMessages,
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
        $message = $this->flashMessages->getMessage('IssueMessage');

        assert($message === null || is_array($message));

        if ($message !== null) {
            /** @psalm-suppress MixedAssignment */
            $message = $message[0];

            assert(is_array($message));
        }

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
                    'uri' => '/news',
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
