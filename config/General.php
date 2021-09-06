<?php

declare(strict_types=1);

namespace Config;

use App\Context\Email\Entity\EmailRecipient;
use App\Context\Email\Entity\EmailRecipientCollection;
use DateTimeZone;

use function assert;
use function dirname;
use function getenv;
use function is_string;

class General
{
    private bool $devMode;
    private string $rootPath;
    private string $publicPath;
    private string $pathToStorageDirectory;
    private string $stripePublishableKey;
    private string $stripeSecretKey;
    private string $stripeCheckoutSessionCompletedSigningSecret;
    private string $siteUrl                   = 'https://www.buzzingpixel.com';
    private string $siteName                  = 'BuzzingPixel';
    private string $systemEmailSenderAddress  = 'info@buzzingpixel.com';
    private string $systemEmailSenderName     = 'BuzzingPixel';
    private string $systemEmailNoReplyAddress = 'noreply@buzzingpixel.com';
    /** @var string[] */
    private array $stylesheets = [];
    /** @var string[] */
    private array $jsFiles         = ['https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js'];
    private string $systemTimeZone = 'US/Central';
    private string $oldSiteUrl;
    private string $oldSiteTransferKey;

    public function __construct()
    {
        $this->devMode = (bool) getenv('DEV_MODE');

        $this->rootPath = dirname(__DIR__);

        $this->publicPath = $this->rootPath . '/public';

        $this->pathToStorageDirectory = $this->rootPath . '/storage';

        /** @phpstan-ignore-next-line */
        $this->stripePublishableKey = (string) getenv(
            'STRIPE_PUBLISHABLE_KEY'
        );

        /** @phpstan-ignore-next-line */
        $this->stripeSecretKey = (string) getenv('STRIPE_SECRET_KEY');

        /** @phpstan-ignore-next-line */
        $this->stripeCheckoutSessionCompletedSigningSecret = (string) getenv(
            'STRIPE_CHECKOUT_SESSION_COMPLETED_SIGNING_SECRET',
        );

        if (getenv('SITE_URL') !== false) {
            $siteUrl = getenv('SITE_URL');

            assert(is_string($siteUrl));

            $this->siteUrl = $siteUrl;
        }

        $systemTimeZone = getenv('SYSTEM_TIME_ZONE');
        if (is_string($systemTimeZone) && $systemTimeZone !== '') {
            $this->systemTimeZone = $systemTimeZone;
        }

        /** @phpstan-ignore-next-line */
        $this->oldSiteUrl = (string) getenv('OLD_SITE_URL');

        /** @phpstan-ignore-next-line */
        $this->oldSiteTransferKey = (string) getenv('OLD_SITE_TRANSFER_KEY');

        if (
            ! $this->devMode() ||
            ! (bool) getenv('USE_DYNAMIC_SITE_URL') ||
            ! isset($_SERVER['HTTP_HOST'])
        ) {
            return;
        }

        $this->siteUrl = 'https://' . ((string) $_SERVER['HTTP_HOST']);
    }

    public function devMode(): bool
    {
        return $this->devMode;
    }

    public function rootPath(): string
    {
        return $this->rootPath;
    }

    public function publicPath(): string
    {
        return $this->publicPath;
    }

    public function pathToStorageDirectory(): string
    {
        return $this->pathToStorageDirectory;
    }

    public function stripePublishableKey(): string
    {
        return $this->stripePublishableKey;
    }

    public function stripeSecretKey(): string
    {
        return $this->stripeSecretKey;
    }

    public function stripeCheckoutSessionCompletedSigningSecret(): string
    {
        return $this->stripeCheckoutSessionCompletedSigningSecret;
    }

    public function siteUrl(): string
    {
        return $this->siteUrl;
    }

    public function siteName(): string
    {
        return $this->siteName;
    }

    public function systemEmailSenderAddress(): string
    {
        return $this->systemEmailSenderAddress;
    }

    public function systemEmailSenderName(): string
    {
        return $this->systemEmailSenderName;
    }

    public function systemEmailNoReplyAddress(): string
    {
        return $this->systemEmailNoReplyAddress;
    }

    /**
     * @return string[]
     */
    public function stylesheets(): array
    {
        return $this->stylesheets;
    }

    /**
     * @return string[]
     */
    public function jsFiles(): array
    {
        return $this->jsFiles;
    }

    /** @var array<string, array<string, string|bool>> */
    public static array $accountMenu = [
        'licenses' => [
            'href' => '/account/licenses',
            'content' => 'Licenses',
            'isActive' => false,
        ],
        'purchases' => [
            'href' => '/account/purchases',
            'content' => 'Purchases',
            'isActive' => false,
        ],
        'profile' => [
            'href' => '/account/profile',
            'content' => 'Profile',
            'isActive' => false,
        ],
        'change-password' => [
            'href' => '/account/change-password',
            'content' => 'Change Password',
            'isActive' => false,
        ],
        'billing-portal' => [
            'href' => '/account/billing-portal',
            'content' => 'Billing Portal',
            'isActive' => false,
        ],
        'log-out' => [
            'href' => '/account/log-out',
            'content' => 'Log Out',
            'isActive' => false,
        ],
    ];

    /**
     * @return array<string, array<string, string|bool>>
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function accountMenu(?string $active = null): array
    {
        $menu = self::$accountMenu;

        if ($active !== null) {
            $menu[$active]['isActive'] = true;
        }

        return $menu;
    }

    /** @var array<string, array<string, string|bool>> */
    public static array $supportMenu = [
        'dashboard' => [
            'href' => '/support',
            'content' => 'Dashboard',
            'isActive' => false,
        ],
        'allIssues' => [
            'href' => '/support/all-issues',
            'content' => 'All Issues',
            'isActive' => false,
        ],
        'myIssues' => [
            'href' => '/support/my-issues',
            'content' => 'My Issues',
            'isActive' => false,
        ],
    ];

    /**
     * @return array<string, array<string, string|bool>>
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function supportMenu(?string $active = null): array
    {
        $menu = self::$supportMenu;

        if ($active !== null) {
            $menu[$active]['isActive'] = true;
        }

        return $menu;
    }

    /** @var array<string, array<string, string|bool>> */
    public static array $adminMenu = [
        'software' => [
            'href' => '/admin/software',
            'content' => 'Software',
            'isActive' => false,
        ],
        'users' => [
            'href' => '/admin/users',
            'content' => 'Users',
            'isActive' => false,
        ],
        'orders' => [
            'href' => '/admin/orders',
            'content' => 'Orders',
            'isActive' => false,
        ],
        'addNewLicense' => [
            'href' => '/admin/new-license',
            'content' => 'New License',
            'isActive' => false,
        ],
        'queue' => [
            'href' => '/admin/queue',
            'content' => 'Queue',
            'isActive' => false,
        ],
        'analytics' => [
            'href' => '/admin/analytics',
            'content' => 'Analytics',
            'isActive' => false,
        ],
    ];

    /**
     * @return array<string, array<string, string|bool>>
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function adminMenu(?string $active = null): array
    {
        $menu = self::$adminMenu;

        if ($active !== null) {
            $menu[$active]['isActive'] = true;
        }

        return $menu;
    }

    /**
     * @return mixed[]
     */
    public function siteBanner(): array
    {
        return [
            'siteBanner' => 'filler',
            'content-disabled' => 'This is a test of the emergency broadcasting system',
            'mobileContent-disabled' => 'Do stuff',
            'link-disabled' => [
                'href' => '#todo',
                'content' => 'Go Here',
            ],
        ];
    }

    /** @phpstan-ignore-next-line  */
    public function contactFormRecipients(): EmailRecipientCollection
    {
        return new EmailRecipientCollection([
            new EmailRecipient(emailAddress: 'tj@buzzingpixel.com'),
        ]);
    }

    public function noReplyRecipient(): EmailRecipient
    {
        return new EmailRecipient(
            emailAddress: $this->systemEmailNoReplyAddress(),
            name: $this->systemEmailSenderName(),
        );
    }

    public function systemTimeZone(): DateTimeZone
    {
        return new DateTimeZone($this->systemTimeZone);
    }

    /** @var string[] */
    public static array $devSubDomains = [
        'acc',
        'acceptance',
        'demo',
        'dev',
        'example',
        'invalid',
        'loc',
        'local',
        'localhost',
        'sandbox',
        'stage',
        'staging',
        'test',
        'testing',
        'ddev',
    ];

    /**
     * @return string[]
     */
    public function devSubDomains(): array
    {
        return self::$devSubDomains;
    }

    public function oldSiteUrl(string $uri = ''): string
    {
        return $this->oldSiteUrl . $uri;
    }

    public function oldSiteTransferKey(): string
    {
        return $this->oldSiteTransferKey;
    }
}
