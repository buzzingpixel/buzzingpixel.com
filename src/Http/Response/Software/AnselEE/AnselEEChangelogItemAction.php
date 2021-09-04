<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE;

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

class AnselEEChangelogItemAction
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
        $slug = (string) $request->getAttribute('slug');

        $changelog = $this->parseChangelogFromMarkdownFile->parse(
            $this->generalConfig->rootPath() . '/src/Http/Response/Software/AnselEE/AnselEEChangelog.md',
        );

        $release = null;

        foreach ($changelog->getReleases() as $loopRelease) {
            $loopSlug = str_replace(
                '.',
                '-',
                $loopRelease->getVersion()
            );

            if ($loopSlug !== $slug) {
                continue;
            }

            $release = $loopRelease;
        }

        if ($release === null) {
            throw new HttpNotFoundException($request);
        }

        $response = $this->responseFactory->createResponse()
            ->withHeader('EnableStaticCache', 'true');

        $response->getBody()->write(
            $this->twig->render(
                'Http/Changelog/ChangelogTemplate.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Ansel for ExpressionEngine Changelog: ' . $release->getVersion(),
                    ),
                    'heading' => 'Ansel for ExpressionEngine Changelog: ' . $release->getVersion(),
                    'navItems' => AnselEEVariables::NAV,
                    'changelog' => new ChangelogPayload([$release]),
                    'baseUri' => AnselEEVariables::CHANGELOG_BASE_URI,
                ],
            ),
        );

        return $response;
    }
}
