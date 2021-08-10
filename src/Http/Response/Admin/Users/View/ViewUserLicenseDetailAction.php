<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Licenses\LicenseApi;
use App\Context\Software\Entities\Software;
use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
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
use function floatval;

class ViewUserLicenseDetailAction
{
    public function __construct(
        private General $config,
        private UserApi $userApi,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
        private GithubMarkdown $markdown,
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

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $software = $license->software();

        assert($software instanceof Software);

        $renewalDate = $license->renewalDate();

        $keyValueSubHeadline = '';

        $keyValueItems = [
            [
                'key' => 'License Key',
                'value' => $license->licenseKey(),
            ],
            [
                'key' => 'Enabled?',
                'value' => $license->isDisabled() ? 'Yes' : 'No',
            ],
        ];

        if ($license->stripeCanceledAt() !== null) {
            $keyValueItems[] = [
                'key' => 'Stripe Canceled At',
                'value' => $license->stripeCanceledAt()->setTimezone(
                    $this->loggedInUser->user()->timezone(),
                )->format('F jS, Y, g:i a'),
            ];
        }

        $expirationDate = $license->expiresAt();

        $actionButtons = [];

        if ($license->isDisabled()) {
            $keyValueSubHeadline = 'disabled by admin';
        } elseif (
            $license->isSubscription() &&
            $license->isNotCanceled() &&
            $renewalDate !== null
        ) {
            $keyValueItems[] = [
                'key' => 'Renews on',
                'value' => $renewalDate->setTimezone(
                    $this->loggedInUser->user()->timezone(),
                )->format('F j, Y'),
            ];

            $keyValueItems[] = [
                'key' => 'Renewal Amount',
                'value' => $license->stripeSubscriptionAmountFormatted(),
            ];

            $actionButtons[] = [
                'colorType' => 'danger',
                'href' => $license->adminCancelSubscriptionLink(),
                'content' => 'Cancel Subscription',
            ];

            if ($license->isExpired()) {
                $actionButtons = [];

                $keyValueSubHeadline = 'Updates have expired';
            } else {
                $keyValueSubHeadline = 'Subscription is active';
            }
        } elseif ($license->isSubscription()) {
            assert($expirationDate instanceof DateTimeImmutable);

            if ($license->isNotExpired()) {
                $keyValueSubHeadline = 'Subscription is not active. ' .
                    'Updates will expire at the end of the period.';

                $keyValueItems[] = [
                    'key' => 'Expires on',
                    'value' => $expirationDate->setTimezone(
                        $this->loggedInUser->user()->timezone(),
                    )->format('F j, Y'),
                ];

                $actionButtons[] = [
                    'href' => $license->adminResumeSubscriptionLink(),
                    'content' => 'Resume Subscription ($' .
                        floatval($software->renewalPriceFormattedNoSymbol()) .
                        '/yr)',
                ];
            } else {
                $keyValueSubHeadline = 'Subscription has expired.';

                $keyValueItems[] = [
                    'key' => 'Expired on',
                    'value' => $expirationDate->setTimezone(
                        $this->loggedInUser->user()->timezone(),
                    )->format('F j, Y'),
                ];
            }
        }

        $keyValueItems[] = [
            'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
            'key' => 'Authorized Domains',
            'value' => [
                'actionLinks' => [
                    [
                        'href' => $license->adminAddAuthorizedDomainLink(),
                        'content' => 'Add Authorized Domain',
                    ],
                ],
                'items' => array_map(
                    static function (string $domain) use (
                        $license
                    ): array {
                        return [
                            'content' => $domain,
                            'links' => [
                                [
                                    'href' => $license->adminDeleteAuthorizedDomainLink(
                                        $domain
                                    ),
                                    'content' => 'Remove',
                                ],
                            ],
                        ];
                    },
                    $license->authorizedDomains(),
                ),
            ],
        ];

        $keyValueItems[] = [
            'key' => 'Stripe Status',
            'value' => $license->stripeStatus(),
        ];

        $keyValueItems[] = [
            'key' => 'Stripe Subscription ID',
            'value' => $license->stripeSubscriptionId(),
        ];

        $keyValueItems[] = [
            'key' => 'Stripe Subscription Item ID',
            'value' => $license->stripeSubscriptionItemId(),
        ];

        $keyValueItems[] = [
            'template' => 'Http/_Infrastructure/Display/Prose.twig',
            'key' => 'User Notes',
            'value' => [
                'content' => $this->markdown->parse($license->userNotes()),
            ],
        ];

        $keyValueItems[] = [
            'template' => 'Http/_Infrastructure/Display/Prose.twig',
            'key' => 'Admin Notes',
            'value' => [
                'content' => $this->markdown->parse($license->adminNotes()),
                'actionLink' => [
                    'href' => $license->adminEditAdminNotesLink(),
                    'content' => 'Edit Admin Notes',
                ],
            ],
        ];

        if ($license->isNotDisabled()) {
            $actionButtons[] = [
                'colorType' => 'danger',
                'href' => '#todo',
                'content' => 'Disable License',
            ];
        } else {
            $actionButtons[] = [
                'href' => '#todo',
                'content' => 'Enable License',
            ];
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminKeyValuePage.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Viewing License | Licenses ' . $user->emailAddress() . ' | Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'tabs' => UserConfig::getUserViewTabs(
                    baseAdminProfileLink: $user->adminBaseLink(),
                    activeTab: 'licenses',
                ),
                'breadcrumbSingle' => [
                    'content' => 'Licenses',
                    'uri' => '/admin/users/' . $user->emailAddress() . '/licenses',
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
                    ['content' => 'License'],
                ],
                'keyValueCard' => [
                    'headline' => 'License for: ' . $software->name() . ' | ' . $user->emailAddress(),
                    'subHeadline' => $keyValueSubHeadline,
                    'actionButtons' => $actionButtons,
                    'items' => $keyValueItems,
                ],
            ],
        ));

        return $response;
    }
}
