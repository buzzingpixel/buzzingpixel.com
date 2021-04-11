<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ViewUserAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private UserApi $userApi,
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
        // TODO: User Search
        // TODO: pagination
        // TODO: Display User Orders
        // TODO: Display User Licenses
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress($emailAddress),
        );

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminKeyValuePage.twig',
            [
                'meta' => new Meta(
                    metaTitle: $user->emailAddress() . ' | Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'breadcrumbSingle' => [
                    'content' => 'Users',
                    'uri' => '/admin/users',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Admin',
                        'uri' => '/admin',
                    ],
                    [
                        'content' => 'Users',
                        'uri' => '/admin/users',
                    ],
                    ['content' => 'View'],
                ],
                'keyValueCard' => [
                    'actionButtons' => [
                        [
                            'colorType' => 'danger',
                            'href' => $user->adminDeleteLink(),
                            'content' => 'Delete',
                        ],
                        [
                            'href' => $user->adminEditLink(),
                            'content' => 'Edit',
                        ],
                    ],
                    'headline' => $user->emailAddress(),
                    'items' => [
                        [
                            'type' => 'heading',
                            'heading' => 'Account Details',
                        ],
                        [
                            'key' => 'Is Admin?',
                            'value' => $user->isAdmin() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Email Address',
                            'value' => $user->emailAddress(),
                        ],
                        [
                            'key' => 'Is Active?',
                            'value' => $user->isActive() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Time Zone',
                            'value' => $user->timezone()->getName(),
                        ],
                        [
                            'type' => 'heading',
                            'heading' => 'Support Profile',
                        ],
                        [
                            'key' => 'Display Name',
                            'value' => $user->supportProfile()->displayName(),
                        ],
                        [
                            'type' => 'heading',
                            'heading' => 'Billing Contact Details',
                        ],
                        [
                            'key' => 'Billing Name',
                            'value' => $user->billingProfile()->billingName(),
                        ],
                        [
                            'key' => 'Billing Company',
                            'value' => $user->billingProfile()->billingCompany(),
                        ],
                        [
                            'key' => 'Billing Phone',
                            'value' => $user->billingProfile()->billingPhone(),
                        ],
                        [
                            'type' => 'heading',
                            'heading' => 'Billing Address',
                        ],
                        [
                            'key' => 'Billing Country / Region',
                            'value' => $user->billingProfile()->billingCountryRegionName(),
                        ],
                        [
                            'key' => 'Billing Address',
                            'value' => $user->billingProfile()->billingAddress(),
                        ],
                        [
                            'key' => 'Billing Address Continued',
                            'value' => $user->billingProfile()->billingAddressContinued(),
                        ],
                        [
                            'key' => 'Billing City',
                            'value' => $user->billingProfile()->billingCity(),
                        ],
                        [
                            'key' => 'Billing State/Province',
                            'value' => $user->billingProfile()->billingStateProvinceName(),
                        ],
                        [
                            'key' => 'Billing Postal Code',
                            'value' => $user->billingProfile()->billingPostalCode(),
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}
