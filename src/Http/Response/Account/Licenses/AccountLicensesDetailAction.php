<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

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

class AccountLicensesDetailAction
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

        if ($license === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $accountMenu = $this->config->accountMenu();

        /** @psalm-suppress MixedArrayAssignment */
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
                'value' => $renewalDate->format('F j, Y'),
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
                    'value' => $expirationDate->format('F j, Y'),
                ];

                $actionButtons[] = [
                    'href' => $license->accountResumeSubscriptionLink(),
                    'content' => 'Resume Subscription',
                ];
            } else {
                $keyValueSubHeadline = 'Subscription has expired. ' .
                    'Restart subscription to keep receiving updates and ' .
                    'support development.';

                $keyValueItems[] = [
                    'key' => 'Expired on',
                    'value' => $expirationDate->format('F j, Y'),
                ];

                $actionButtons[] = [
                    'href' => $license->accountStartNewSubscriptionLink(),
                    'content' => 'Start New Updates Subscription',
                ];
            }
        }

        if (! $license->isDisabled()) {
            $keyValueItems[] = [
                'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
                'key' => 'Authorized Domains',
                'value' => [
                    'actionLinks' => [
                        [
                            'href' => $license->accountAddAuthorizedDomainLink(),
                            'content' => 'Add Authorized Domain',
                        ],
                    ],
                    'items' => array_map(
                        static function (string $domain) use ($license): array {
                            return [
                                'content' => $domain,
                                'links' => [
                                    [
                                        'href' => $license->accountDeleteAuthorizedDomainLink(
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
        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/AccountKeyValuePage.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Viewing License | Account | Admin',
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
            ),
        );

        return $response;
    }
}
