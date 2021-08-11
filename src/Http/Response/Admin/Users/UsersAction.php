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
use Slim\Exception\HttpNotFoundException;
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
     * @throws HttpNotFoundException
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

        $search = ($queryParams['search'] ?? '');

        $queryBuilder = (new UserQueryBuilder())
            ->withOrderBy('emailAddress', 'asc')
            ->withOffset(($pageNum * self::LIMIT) - self::LIMIT)
            ->withLimit(self::LIMIT);

        if ($search !== '') {
            $queryBuilder = $queryBuilder->withSearchField(
                'emailAddress',
                $search,
            );
        }

        $users = $this->userApi->fetchUsers($queryBuilder);

        $pagination = (new Pagination())
            ->withQueryStringBased(true)
            ->withBase('/admin/users')
            ->withQueryStringFromArray($queryParams)
            ->withCurrentPage($pageNum)
            ->withPerPage(self::LIMIT)
            ->withTotalResults($this->userApi->fetchTotalUsers(
                queryBuilder: $queryBuilder,
            ));

        if ($pageNum > 1 && $users->count() < 1) {
            throw new HttpNotFoundException($request);
        }

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'searchAction' => '/admin/users',
                    'searchPlaceholder' => 'Search by email address',
                    'searchValue' => $search,
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
                        },
                    ),
                ],
            ],
        ));

        return $response;
    }
}
