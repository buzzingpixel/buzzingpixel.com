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

class AccountLicenseEditNotesAction
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
            queryBuilder: (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withHasBeenUpgraded(false)
                ->withLicenseKey($licenseKey),
        );

        if ($license === null) {
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
                    metaTitle: 'Edit Notes | Licenses | Account',
                ),
                'accountMenu' => $accountMenu,
                'headline' => 'Edit Notes',
                'subHeadline' => 'of license for ' . $software->name() . ': ' . $license->licenseKey(),
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

                    ['content' => 'Edit Notes'],
                ],
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Submit Edits',
                    'cancelAction' => $license->accountLink(),
                    'formAction' => $license->accountEditNotesLink(),
                    'inputs' => [
                        [
                            'limitedWidth' => false,
                            'template' => 'TextArea',
                            'label' => 'Notes',
                            'subHeading' => 'Use Markdown to format',
                            'name' => 'notes',
                            'attrs' => ['rows' => '15'],
                            'value' => $license->userNotes(),
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}
