<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Treasury;

use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TreasuryChangelogAction
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
            ->withHeader(name: 'EnableStaticCache', value:'true');

        $response->getBody()->write(
            $this->twig->render(
                name: 'Http/Changelog/ChangelogTemplate.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Treasury for ExpressionEngine Changelog',
                    ),
                    'heading' => 'Treasury Changelog',
                    'navItems' => TreasuryVariables::NAV,
                    'changelog' => $this->parseChangelogFromMarkdownFile->parse(
                        location: $this->generalConfig->rootPath() . '/src/Http/Response/Software/Treasury/TreasuryChangelog.md',
                    ),
                    'baseUri' => TreasuryVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
