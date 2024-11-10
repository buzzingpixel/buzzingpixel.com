<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft;

use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnselCraftAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig
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
                '@software/AnselCraft/AnselCraftTemplate.twig',
                [
                    'meta' => new Meta(
                        metaTitle: 'Ansel Image Field Type for Craft CMS',
                    ),
                    'navItems' => AnselCraftVariables::NAV,
                    'ctas' => [],
                    // 'ctas' => [
                    //     [
                    //         'caret' => true,
                    //         'href' => 'https://plugins.craftcms.com/ansel',
                    //         'content' => 'On the Plugin Store',
                    //         'type' => 'primary',
                    //     ],
                    //     [
                    //         'caret' => true,
                    //         'href' => 'https://github.com/buzzingpixel/ansel-craft',
                    //         'content' => 'On GitHub',
                    //         'type' => 'secondary',
                    //     ],
                    //     [
                    //         'caret' => true,
                    //         'href' => 'https://packagist.org/packages/buzzingpixel/ansel-craft',
                    //         'content' => 'On Packagist',
                    //         'type' => 'secondary',
                    //     ],
                    // ],
                ],
            ),
        );

        return $response;
    }
}
