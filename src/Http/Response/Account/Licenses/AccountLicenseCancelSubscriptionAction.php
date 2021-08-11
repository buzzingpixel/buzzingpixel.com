<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Software\Entities\Software;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use cebe\markdown\GithubMarkdown;
use Config\General;
use DateTimeImmutable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

use function array_map;
use function assert;

class AccountLicenseCancelSubscriptionAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
        private GithubMarkdown $markdown,
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

        if (
            $license === null ||
            $license->isNotSubscription() ||
            $license->isNotActive()
        ) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $accountMenu = $this->config->accountMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $accountMenu['licenses']['isActive'] = true;

        $software = $license->software();

        assert($software instanceof Software);

        $renewalDate = $license->renewalDate();

        assert($renewalDate instanceof DateTimeImmutable);

        $content = 'When you cancel your subscription, you will no longer ' .
            'receive updates or support at the end of the period ending on ' .
            $renewalDate->setTimezone(
                $this->loggedInUser->user()->timezone(),
            )->format('F j, Y') . '. ';

        $listItems = [];

        // This shouldn't be necessary anymore since we refactored checkout to
        // only allow one license purchase at a time for just this reason. But
        // It doesn't really hurt anything
        $otherLicenses = $this->licenseApi->fetchLicenses(
            (new LicenseQueryBuilder())
                ->withStripeSubscriptionId(
                    $license->stripeSubscriptionId()
                ),
        )->filter(
            static fn (License $l) => $l->id() !== $license->id()
        );

        if ($otherLicenses->count() > 0) {
            $content .= 'Please note also, that because of the way orders and ' .
                'subscriptions work in Stripe\'s system, that any ' .
                'subscriptions purchased as part of the same order will be ' .
                'canceled along with this one. Those licenses are:';

            $listItems = $otherLicenses->mapToArray(
                static function (License $l): array {
                    $s = $l->software();

                    assert($s instanceof Software);

                    return [
                        'href' => $l->accountLink(),
                        'content' => 'License for: ' . $s->name() . ' ' .
                            $l->licenseKey(),
                    ];
                }
            );
        }

        $keyValueItems = [
            [
                'key' => 'License Key',
                'value' => $license->licenseKey(),
            ],
            [
                'key' => 'Software Version',
                'value' => $license->majorVersion(),
            ],
            [
                'key' => 'Renews on',
                'value' => $renewalDate->setTimezone(
                    $this->loggedInUser->user()->timezone(),
                )->format('F j, Y'),
            ],
            [
                'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
                'key' => 'Authorized Domains',
                'value' => [
                    'items' => array_map(
                        static function (string $domain): array {
                            return ['content' => $domain];
                        },
                        $license->authorizedDomains(),
                    ),
                ],
            ],
            [
                'template' => 'Http/_Infrastructure/Display/Prose.twig',
                'key' => 'Notes',
                'value' => [
                    'content' => $this->markdown->parse($license->userNotes()),
                ],
            ],
        ];

        $response = $this->responseFactory->createResponse();

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/AccountConfirmationForm.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Confirm Subscription Cancellation | Licenses | Account',
                    ),
                    'accountMenu' => $accountMenu,
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

                        ['content' => 'Cancel Subscription'],
                    ],
                    'headline' => 'Cancel Subscription',
                    'content' => $content,
                    'listItems' => $listItems,
                    'actionButtonContent' => 'Confirm Cancellation',
                    'keyValueCard' => [
                        'headline' => 'License for: ' . $software->name(),
                        'items' => $keyValueItems,
                    ],
                ],
            ),
        );

        return $response;
    }
}
