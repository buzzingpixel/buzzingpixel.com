<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Profile;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Twig\Environment as TwigEnvironment;

use function json_encode;

class AccountProfileAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private LoggedInUser $loggedInUser,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $accountMenu = $this->config->accountMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $accountMenu['profile']['isActive'] = true;

        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/Profile/AccountProfile.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Profile',
                    ),
                    'accountMenu' => $accountMenu,
                    'headline' => 'Profile',
                    'formConfig' => [
                        'submitContent' => 'Update Profile',
                        'formAction' => '/account/profile',
                        'formAttrs' => [
                            'x-data' => json_encode([
                                'data' => ['countryRegion' => $this->loggedInUser->user()->billingProfile()->billingCountryRegion()],
                            ]),
                        ],
                        'inputs' => UserConfig::getProfileFormConfigInputs(
                            [],
                            $this->loggedInUser->user(),
                        ),
                    ],
                ],
            ),
        );

        return $response;
    }
}
