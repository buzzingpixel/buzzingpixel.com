<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Treasury;

use App\Content\Changelog\ChangelogPayload;
use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function str_replace;

class TreasuryChangelogItemAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private ParseChangelogFromMarkdownFile $parseChangelogFromMarkdownFile,
        private General $generalConfig,
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
            location: $this->generalConfig->rootPath() . '/src/Http/Response/Software/Treasury/TreasuryChangelog.md',
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
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                'Http/Changelog/ChangelogTemplate.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Treasury for ExpressionEngine Changelog: ' . $release->getVersion(),
                    ),
                    'heading' => 'Treasury Changelog: ' . $release->getVersion(),
                    'navItems' => TreasuryVariables::NAV,
                    'changelog' => new ChangelogPayload([$release]),
                    'baseUri' => TreasuryVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
