<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\LicenseApi;
use App\Context\Software\Entities\Software;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

use function assert;

class AccountLicenseAddAuthorizedDomainAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $licenseKey = (string) $request->getAttribute('licenseKey');

        $license = $this->licenseApi->fetchOneLicense(
            (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withLicenseKey($licenseKey),
        );

        if ($license === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        if ($license->hasReachedMaxDomains()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $response = $this->responseFactory->createResponse();

        $accountMenu = $this->config->accountMenu();

        $accountMenu['licenses']['isActive'] = true;

        $software = $license->software();

        assert($software instanceof Software);

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Account/AccountForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Add Authorized Domain | Licenses | Account',
                ),
                'accountMenu' => $accountMenu,
                'headline' => 'Add Authorized Domain',
                'subHeadline' => 'to license for ' . $software->name() . ': ' . $license->licenseKey(),
                'breadcrumbSingle' => [
                    'content' => 'License',
                    'uri' => $license->accountLink(),
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Account',
                        'uri' => '/account',
                    ],
                    [
                        'content' => 'Licenses',
                        'uri' => '/account/licenses',
                    ],
                    [
                        'content' => 'License',
                        'uri' => $license->accountLink(),
                    ],

                    ['content' => 'Add Authorized Domain'],
                ],
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Add Authorized Domain',
                    'cancelAction' => $license->accountLink(),
                    'formAction' => $license->accountAddAuthorizedDomainLink(),
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
