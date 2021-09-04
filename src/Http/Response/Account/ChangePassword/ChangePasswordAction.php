<?php

declare(strict_types=1);

namespace App\Http\Response\Account\ChangePassword;

use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ChangePasswordAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $accountMenu = $this->config->accountMenu();

        $accountMenu['change-password']['isActive'] = true;

        $response->getBody()->write(
            $this->twig->render(
                '@app/Http/Response/Account/ChangePassword/ChangePassword.twig',
                [
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
