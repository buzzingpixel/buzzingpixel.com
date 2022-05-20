<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Http\Response\Account\Licenses\Downloads\Factories\VersionFromLicenseFactory;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use cebe\markdown\GithubMarkdown;
use Config\General;
use DateTimeImmutable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

use function array_map;
use function array_merge;
use function assert;
use function base64_encode;
use function floatval;
use function implode;
use function version_compare;

class AccountLicensesDetailAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LicenseApi $licenseApi,
        private GithubMarkdown $markdown,
        private SoftwareApi $softwareApi,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
        private VersionFromLicenseFactory $versionFromLicenseFactory,
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

        $accountMenu = $this->config->accountMenu();

        $accountMenu['licenses']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $software = $license->software();

        assert($software instanceof Software);

        $keyValueItems = [
            [
                'key' => 'License Key',
                'value' => $license->licenseKey(),
            ],
        ];

        if (! $license->isDisabled()) {
            $keyValueItems[] = [
                'key' => 'Software Version',
                'value' => $license->majorVersion(),
            ];
        }

        $downloadValue = $this->getDownloadKeyValueItem(license: $license);

        if ($downloadValue !== null) {
            $keyValueItems = array_merge(
                $keyValueItems,
                $downloadValue,
            );
        }

        $keyValueSubHeadline = '';

        $renewalDate = $license->renewalDate();

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
                'href' => $license->accountCancelSubscriptionLink(),
                'content' => 'Cancel Subscription',
            ];

            if ($license->isExpired()) {
                $actionButtons = [
                    [
                        'href' => $license->accountStartNewSubscriptionLink(),
                        'content' => 'Start New Updates Subscription',
                    ],
                ];

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
                    'href' => $license->accountResumeSubscriptionLink(),
                    'content' => 'Resume Subscription ($' .
                        floatval($software->renewalPriceFormattedNoSymbol()) .
                        '/yr)',
                ];
            } else {
                $keyValueSubHeadline = 'Subscription has expired. ' .
                    'Restart subscription to keep receiving updates and ' .
                    'support development.';

                $keyValueItems[] = [
                    'key' => 'Expired on',
                    'value' => $expirationDate->setTimezone(
                        $this->loggedInUser->user()->timezone(),
                    )->format('F j, Y'),
                ];

                $actionButtons[] = [
                    'href' => $license->accountStartNewSubscriptionLink(),
                    'content' => 'Start New Subscription ($' .
                        floatval($software->renewalPriceFormattedNoSymbol()) .
                        '/yr)',
                ];
            }

            // Not a subscription
        } else {
            $software = $license->softwareGuarantee();

            if ($software->isSubscription()) {
                $softwareVersion = $software
                    ->versions()
                    ->first()
                    ->majorVersion();

                $versionIsOutdated = version_compare(
                    $license->majorVersion(),
                    $softwareVersion,
                    '<',
                );

                $renewalPrice = $software->renewalPriceFormattedNoSymbol();

                if ($versionIsOutdated) {
                    $keyValueSubHeadline = 'In order to update to the newest ' .
                        'major version (' .
                        $software->versions()->first()->version() .
                        '), click the "start subscription" button.';

                    $actionButtons[] = [
                        'href' => $license->accountStartNewSubscriptionLink(),
                        'content' => 'Start Subscription ($' .
                            floatval($renewalPrice) .
                            '/yr)',
                    ];
                }
            }
        }

        if (! $license->isDisabled()) {
            $addAuthorizedDomainLinks = [];

            if ($license->hasNotReachedMaxDomains()) {
                $addAuthorizedDomainLinks[] = [
                    'href' => $license->accountAddAuthorizedDomainLink(),
                    'content' => 'Add Authorized Domain',
                ];
            }

            $keyValueItems[] = [
                'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
                'key' => 'Authorized Domains',
                'subKey' => 'max allowed: ' . License::MAX_AUTHORIZED_DOMAINS,
                'value' => [
                    'actionLinks' => $addAuthorizedDomainLinks,
                    'items' => array_map(
                        static function (string $domain) use ($license): array {
                            return [
                                'content' => $domain,
                                'links' => [
                                    [
                                        'href' => $license->accountDeleteAuthorizedDomainLink(
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

            $keyValueItems[] = [
                'template' => 'Http/_Infrastructure/Display/Prose.twig',
                'key' => 'Notes',
                'value' => [
                    'content' => $this->markdown->parse($license->userNotes()),
                    'actionLink' => [
                        'href' => $license->accountEditNotesLink(),
                        'content' => 'Edit Notes',
                    ],
                ],
            ];
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Account/AccountKeyValuePage.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Viewing License | Licenses | Account',
                ),
                'accountMenu' => $accountMenu,
                'breadcrumbSingle' => [
                    'content' => 'Licenses',
                    'uri' => '/account/licenses',
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
                    ['content' => 'View'],
                ],
                'keyValueCard' => [
                    'headline' => 'License for: ' . $software->name(),
                    'subHeadline' => $keyValueSubHeadline,
                    'actionButtons' => $actionButtons,
                    'items' => $keyValueItems,
                ],
            ],
        ));

        return $response;
    }

    /**
     * @return mixed[]|null
     */
    private function getDownloadKeyValueItem(
        License $license,
    ): ?array {
        if ($license->softwareGuarantee()->isBundle()) {
            return array_map(
                function (string $slug) use ($license): array {
                    $software = $this->softwareApi->fetchOneSoftware(
                        queryBuilder: (new SoftwareQueryBuilder())
                            ->withSlug($slug),
                    );

                    assert($software instanceof Software);

                    $version = $software->versions()->filter(
                        static fn (
                            SoftwareVersion $v
                        ) => $v->downloadFile() !== '',
                    )->first();

                    $name = $version->softwareGuarantee()->name();

                    $versionStr = $version->version();

                    return [
                        'template' => 'Http/_Infrastructure/Display/ActionButton.twig',
                        'key' => 'Download ' . $name,
                        'value' => [
                            'content' => 'Download ' . $name . ' ' . $versionStr,
                            'href' => '/' . implode(
                                '/',
                                [
                                    'account',
                                    'licenses',
                                    $license->licenseKey(),
                                    'download',
                                    $slug,
                                ],
                            ),
                            'download' => true,
                        ],
                    ];
                },
                $license->softwareGuarantee()->bundledSoftware(),
            );
        }

        $version = $this->versionFromLicenseFactory->getVersion(
            $license,
        );

        if ($version === null) {
            return null;
        }

        return [
            $this->getDownloadKeyValueItemActual(
                license: $license,
                version: $version,
            ),
        ];
    }

    /**
     * @return mixed[]
     */
    private function getDownloadKeyValueItemActual(
        License $license,
        SoftwareVersion $version,
    ): array {
        $name = $version->softwareGuarantee()->name();

        $versionStr = $version->version();

        return [
            'template' => 'Http/_Infrastructure/Display/ActionButton.twig',
            'key' => 'Download',
            'value' => [
                'content' => 'Download ' . $name . ' ' . $versionStr,
                'href' => '/account/licenses/' . $license->licenseKey() . '/download',
                'download' => true,
            ],
        ];
    }
}
