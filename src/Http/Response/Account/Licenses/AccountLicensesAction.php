<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\LicenseApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AccountLicensesAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
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

        $accountMenu['licenses']['isActive'] = true;

        $licenses = $this->licenseApi->fetchLicenses(
            (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withHasBeenUpgraded(false)
                ->withOrderBy('id', 'desc'),
        );

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Account/Licenses/AccountLicenses.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Licenses | Account',
                ),
                'accountMenu' => $accountMenu,
                'headline' => 'Licenses',
                'licenses' => $licenses,
            ],
        ));

        return $response;
    }
}
