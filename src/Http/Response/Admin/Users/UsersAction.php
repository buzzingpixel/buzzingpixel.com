<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users;

use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UsersAction
{
    private const LIMIT = 20;

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
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string[] $queryParams */
        $queryParams = $request->getQueryParams();

        $pageNum = (int) ($queryParams['page'] ?? 1);

        if ($pageNum < 1) {
            $pageNum = 1;
        }

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $users = $this->userApi->fetchUsers(
            (new UserQueryBuilder())
                ->withOrderBy('emailAddress', 'asc')
                ->withOffset(($pageNum * self::LIMIT) - self::LIMIT)
                ->withLimit(self::LIMIT),
        );

        $pagination = (new Pagination())
            ->withQueryStringBased(true)
            ->withBase('/admin/users')
            ->withQueryStringFromArray($queryParams)
            ->withCurrentPage($pageNum)
            ->withPerPage(self::LIMIT)
            ->withTotalResults($this->userApi->fetchTotalUsers());

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'pagination' => $pagination,
                    'actionButtons' => [
                        [
                            'href' => '/admin/users/create',
                            'content' => 'New User',
                        ],
                    ],
                    'headline' => 'Users',
                    'noResultsContent' => 'There are no users yet.',
                    'items' => $users->mapToArray(
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
