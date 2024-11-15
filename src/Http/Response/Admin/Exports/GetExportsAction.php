<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Exports;

use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;

class GetExportsAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $adminMenu = $this->config->adminMenu();

        $adminMenu['exports']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/Exports/ExportsInterface.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Exports | Admin',
                ),
                'accountMenu' => $adminMenu,
            ]
        ));

        return $response;
    }
}
