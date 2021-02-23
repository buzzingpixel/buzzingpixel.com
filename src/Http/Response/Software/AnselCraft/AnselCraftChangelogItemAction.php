<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft;

use App\Content\Changelog\ChangelogPayload;
use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function str_replace;

class AnselCraftChangelogItemAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private ParseChangelogFromMarkdownFile $parseChangelogFromMarkdownFile
    ) {
    }

    /**
     * @throws HttpNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $slug = (string) $request->getAttribute(name: 'slug');

        $changelog = $this->parseChangelogFromMarkdownFile->parse(
            location: 'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md'
        );

        $release = null;

        foreach ($changelog->getReleases() as $loopRelease) {
            $loopSlug = str_replace(
                search: '.',
                replace: '-',
                subject: $loopRelease->getVersion()
            );

            if ($loopSlug !== $slug) {
                continue;
            }

            $release = $loopRelease;
        }

        if ($release === null) {
            throw new HttpNotFoundException(request: $request);
        }

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
                    'changelog' => new ChangelogPayload([$release]),
                    'baseUri' => AnselCraftVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
