<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\PaginatedIndex;

use App\Context\Users\Entities\User;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaginatedUsersResponder implements PaginatedUsersResponderContract
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private UserResult $userResult,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function respond(): ResponseInterface
    {
        $adminMenu = $this->config->adminMenu();

        $adminMenu['users']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'pagination' => $this->userResult->pagination(),
                    'headline' => 'Users',
                    'searchAction' => '/admin/users',
                    'noResultsContent' => 'No results.',
                    'searchPlaceholder' => 'Search users',
                    'searchValue' => $this->userResult->searchTerm(),
                    'actionButtons' => [
                        [
                            'href' => '/admin/users/create',
                            'content' => 'New User',
                        ],
                    ],
                    'items' => $this->userResult->users()->mapToArray(
                        static function (User $user): array {
                            return [
                                'href' => $user->adminBaseLink(),
                                'column1Headline' => $user->emailAddress(),
                                'column1SubHeadline' => 'Display Name: ' . $user->supportProfile()->displayName(),
                                'column2Headline' => 'Billing Name:',
                                'column2SubHeadline' => $user->billingProfile()->billingName(),
                            ];
                        },
                    ),
                ],
            ],
        ));

        return $response;
    }
}
