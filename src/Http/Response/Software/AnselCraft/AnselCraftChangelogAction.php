<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft;

use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnselCraftChangelogAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private ParseChangelogFromMarkdownFile $parseChangelogFromMarkdownFile
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
                name: '@software/AnselCraft/AnselCraftChangelogTemplate.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Ansel for Craft CMS Changelog',
                    ),
                    'navItems' => AnselCraftVariables::NAV,
                    'changelog' => $this->parseChangelogFromMarkdownFile->parse(
                        location: 'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md'
                    ),
                    'baseUri' => AnselCraftVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
