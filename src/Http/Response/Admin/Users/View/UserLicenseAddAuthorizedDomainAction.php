<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Licenses\LicenseApi;
use App\Context\Software\Entities\Software;
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

use function assert;

class UserLicenseAddAuthorizedDomainAction
{
    public function __construct(
        private General $config,
        private UserApi $userApi,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
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

        $licenseKey = (string) $request->getAttribute('licenseKey');

        $license = $this->licenseApi->fetchOneLicense(
            queryBuilder: (new LicenseQueryBuilder())
                ->withLicenseKey($licenseKey)
                ->withUserId($user->id()),
        );

        if ($license === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        if ($license->hasReachedMaxDomains()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $software = $license->software();

        assert($software instanceof Software);

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Authorized Domains | License | Licenses ' . $user->emailAddress() . ' | Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'tabs' => UserConfig::getUserViewTabs(
                    baseAdminProfileLink: $user->adminBaseLink(),
                    activeTab: 'licenses',
                ),
                'breadcrumbSingle' => [
                    'content' => 'License',
                    'uri' => $license->adminLink(),
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
                    [
                        'content' => 'Licenses',
                        'uri' => '/admin/users/' . $user->emailAddress() . '/licenses',
                    ],
                    [
                        'content' => 'License',
                        'uri' => $license->adminLink(),
                    ],
                ],
                'headline' => 'Add Authorized Domain',
                'subHeadline' => 'to license for ' . $software->name() . ': ' . $license->licenseKey(),
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Add Authorized Domain',
                    'cancelAction' => $license->adminLink(),
                    'formAction' => $license->adminAddAuthorizedDomainLink(),
                    'inputs' => [
                        [
                            'limitedWidth' => false,
                            'label' => 'Domain Name',
                            'subHeading' => 'Omit subdomains (including "www") and http(s)://&nbsp;prefix. Example: "buzzingpixel.com"',
                            'name' => 'domain_name',
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}
