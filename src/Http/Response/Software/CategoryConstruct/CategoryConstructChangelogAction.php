<?php

declare(strict_types=1);

namespace App\Http\Response\Software\CategoryConstruct;

use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CategoryConstructChangelogAction
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
                        metaTitle: 'Category Construct for ExpressionEngine Changelog',
                    ),
                    'heading' => 'Category Construct Changelog',
                    'navItems' => CategoryConstructVariables::NAV,
                    'changelog' => $this->parseChangelogFromMarkdownFile->parse(
                        location: $this->generalConfig->rootPath() . '/src/Http/Response/Software/CategoryConstruct/CategoryConstructChangelog.md',
                    ),
                    'baseUri' => CategoryConstructVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
