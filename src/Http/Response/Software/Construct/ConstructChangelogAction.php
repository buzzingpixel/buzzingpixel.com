<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Construct;

use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ConstructChangelogAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private ParseChangelogFromMarkdownFile $parseChangelogFromMarkdownFile,
        private General $generalConfig,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                'Http/Changelog/ChangelogTemplate.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Construct for ExpressionEngine Changelog',
                    ),
                    'heading' => 'Construct Changelog',
                    'navItems' => ConstructVariables::NAV,
                    'changelog' => $this->parseChangelogFromMarkdownFile->parse(
                        location: $this->generalConfig->rootPath() . '/src/Http/Response/Software/Construct/ConstructChangelog.md',
                    ),
                    'baseUri' => ConstructVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
