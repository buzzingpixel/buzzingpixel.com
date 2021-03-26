<?php

declare(strict_types=1);

namespace App\Http\Response\Account\ChangePassword;

use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class ChangePasswordAction
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
        $accountMenu['change-password']['isActive'] = true;

        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/ChangePassword/ChangePassword.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Change Password',
                    ),
                    'accountMenu' => $accountMenu,
                    'headline' => 'Change Password',
                ],
            ),
        );

        return $response;
    }
}
