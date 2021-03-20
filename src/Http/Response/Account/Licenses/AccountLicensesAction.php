<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class AccountLicensesAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $accountMenu = $this->config->accountMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $accountMenu['licenses']['isActive'] = true;

        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/Licenses/AccountLicenses.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Licenses',
                    ),
                    'accountMenu' => $accountMenu,
                    'headline' => 'Licenses',
                ],
            ),
        );

        return $response;
    }
}
