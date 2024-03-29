<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

class ViewUserLicensesAction
{
    public function __construct(
        private General $config,
        private UserApi $userApi,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress($emailAddress),
        );

        if ($user === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Licenses ' . $user->emailAddress() . ' | Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'tabs' => UserConfig::getUserViewTabs(
                    baseAdminProfileLink: $user->adminBaseLink(),
                    activeTab: 'licenses',
                ),
                'breadcrumbSingle' => [
                    'content' => 'Profile',
                    'uri' => '/admin/users/' . $user->emailAddress(),
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
                    [
                        'content' => 'Profile',
                        'uri' => '/admin/users/' . $user->emailAddress(),
                    ],
                    ['content' => 'Licenses'],
                ],
                'stackedListTwoColumnConfig' => [
                    'headline' => 'Licenses for ' . $user->emailAddress(),
                    'noResultsContent' => 'There are no licenses for this user yet.',
                    'items' => $this->licenseApi->fetchLicenses(
                        queryBuilder: (new LicenseQueryBuilder())
                            ->withUserId($user->id())
                            ->withOrderBy('id', 'desc'),
                    )->mapToArray(
                        function (License $license): array {
                            return $license->getStackedListTwoColumn(
                                loggedInUser: $this->loggedInUser,
                            );
                        }
                    ),
                ],
            ],
        ));

        return $response;
    }
}
