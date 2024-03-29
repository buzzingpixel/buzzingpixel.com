<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Licenses\Entities\License;
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
use function base64_encode;
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
                'value' => $license->isNotDisabled() ? 'Yes' : 'No',
            ],
        ];

        $stripeCanceledAt = $license->stripeCanceledAt();

        if ($stripeCanceledAt !== null) {
            $keyValueItems[] = [
                'key' => 'Stripe Canceled At',
                'value' => $stripeCanceledAt->setTimezone(
                    $this->loggedInUser->user()->timezone(),
                )->format('F jS, Y, g:i a'),
            ];
        }

        $expirationDate = $license->expiresAt();

        $actionButtons = [];

        if ($license->isNotCanceled() && $renewalDate !== null) {
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
        }

        if ($license->isDisabled()) {
            $keyValueSubHeadline = 'disabled by admin';
        } elseif (
            $license->isSubscription() &&
            $license->isNotCanceled() &&
            $renewalDate !== null
        ) {
            if ($license->isExpired()) {
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

        $addAuthorizedDomainActionLinks = [];

        if ($license->hasNotReachedMaxDomains()) {
            $addAuthorizedDomainActionLinks[] = [
                'href' => $license->adminAddAuthorizedDomainLink(),
                'content' => 'Add Authorized Domain',
            ];
        }

        $keyValueItems[] = [
            'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
            'key' => 'Authorized Domains',
            'subKey' => 'max allowed: ' . License::MAX_AUTHORIZED_DOMAINS,
            'value' => [
                'actionLinks' => $addAuthorizedDomainActionLinks,
                'items' => array_map(
                    static function (string $domain) use (
                        $license
                    ): array {
                        return [
                            'content' => $domain,
                            'links' => [
                                [
                                    'href' => $license->adminDeleteAuthorizedDomainLink(
                                        base64_encode($domain),
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

        if ($license->stripeStatus() !== '') {
            $keyValueItems[] = [
                'key' => 'Stripe Status',
                'value' => $license->stripeStatus(),
            ];
        }

        if ($license->stripeSubscriptionId() !== '') {
            $keyValueItems[] = [
                'key' => 'Stripe Subscription ID',
                'value' => $license->stripeSubscriptionId(),
            ];
        }

        if ($license->stripeSubscriptionItemId() !== '') {
            $keyValueItems[] = [
                'key' => 'Stripe Subscription Item ID',
                'value' => $license->stripeSubscriptionItemId(),
            ];
        }

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
                'href' => $license->adminDisableLicenseLink(),
                'content' => 'Disable License',
            ];
        } else {
            $actionButtons[] = [
                'href' => $license->adminEnableLicenseLink(),
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
