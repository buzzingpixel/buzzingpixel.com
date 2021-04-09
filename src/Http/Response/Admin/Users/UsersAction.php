<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users;

use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UsersAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private UserApi $userApi,
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

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'actionButtons' => [
                        [
                            'href' => '/admin/users/create',
                            'content' => 'New User',
                        ],
                    ],
                    'headline' => 'Users',
                    'noResultsContent' => 'There are no users yet.',
                    'items' => $this->userApi->fetchUsers(
                        (new UserQueryBuilder())
                            ->withOrderBy('createdAt', 'desc')
                    )->mapToArray(
                        static function (User $user): array {
                            return [
                                'href' => $user->adminBaseLink(),
                                'column1Headline' => $user->emailAddress(),
                                'column1SubHeadline' => 'Display Name: ' . $user->supportProfile()->displayName(),
                                'column2Headline' => 'Billing Name:',
                                'column2SubHeadline' => $user->billingProfile()->billingName(),
                            ];
                        }
                    ),
                ],
            ],
        ));

        return $response;
    }
}