<?php

declare(strict_types=1);

namespace Config;

use App\Context\Email\Entity\EmailRecipient;
use App\Context\Email\Entity\EmailRecipientCollection;
use Config\Abstractions\SimpleModel;

use function assert;
use function dirname;
use function getenv;
use function is_string;

/**
 * @method bool devMode()
 * @method string rootPath()
 * @method string publicPath()
 * @method string pathToStorageDirectory()
 * @method string siteUrl()
 * @method string siteName()
 * @method string systemEmailSenderAddress()
 * @method string systemEmailSenderName()
 * @method string stripePublishableKey()
 * @method string stripeSecretKey()
 * @method string stripeCheckoutSessionCompletedSigningSecret()
 * @method array stylesheets()
 * @method array jsFiles()
 * @method array siteBanner()
 */
class General extends SimpleModel
{
    public function __construct()
    {
        $rootPath = dirname(__DIR__);

        static::$devMode = (bool) getenv('DEV_MODE');

        static::$rootPath = $rootPath;

        static::$publicPath = $rootPath . '/public';

        static::$pathToStorageDirectory = $rootPath . '/storage';

        /** @phpstan-ignore-next-line */
        static::$stripePublishableKey = (string) getenv(
            'STRIPE_PUBLISHABLE_KEY'
        );

        /** @phpstan-ignore-next-line */
        static::$stripeSecretKey = (string) getenv('STRIPE_SECRET_KEY');

        /** @phpstan-ignore-next-line */
        static::$stripeCheckoutSessionCompletedSigningSecret = (string) getenv(
            'STRIPE_CHECKOUT_SESSION_COMPLETED_SIGNING_SECRET',
        );

        if (getenv('SITE_URL') !== false) {
            $siteUrl = getenv('SITE_URL');

            assert(is_string($siteUrl));

            static::$siteUrl = $siteUrl;
        }

        if (
            ! static::$devMode ||
            ! (bool) getenv('USE_DYNAMIC_SITE_URL') ||
            ! isset($_SERVER['HTTP_HOST'])
        ) {
            return;
        }

        static::$siteUrl = 'https://' . ((string) $_SERVER['HTTP_HOST']);
    }

    public static bool $devMode = false;

    public static string $rootPath = '';

    public static string $publicPath = '';

    public static string $pathToStorageDirectory = '';

    public static string $siteUrl = 'https://www.buzzingpixel.com';

    public static string $siteName = 'BuzzingPixel';

    public static string $systemEmailSenderAddress = 'info@buzzingpixel.com';

    public static string $systemEmailSenderName = 'BuzzingPixel';

    public static string $stripePublishableKey = '';

    public static string $stripeSecretKey = '';

    public static string $stripeCheckoutSessionCompletedSigningSecret = '';

    /** @var string[] */
    public static array $stylesheets = [];

    /** @var string[] */
    public static array $jsFiles = ['https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js'];

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
        'publicIssues' => [
            'href' => '/support/public',
            'content' => 'Public Issues',
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

    /** @var mixed[] */
    public static array $siteBanner = [
        'siteBanner' => 'filler',
        'explanation' => 'Keep PHPCS from complaining when items commented out',
        // 'content' => 'This is a test of the emergency broadcasting system',
        // 'mobileContent' => 'Do stuff',
        // 'link' => [
        //     'href' => '#todo',
        //     'content' => 'Go Here',
        // ],
    ];

    /** @phpstan-ignore-next-line  */
    public function contactFormRecipients(): EmailRecipientCollection
    {
        return new EmailRecipientCollection([
            new EmailRecipient(emailAddress: 'tj@buzzingpixel.com'),
        ]);
    }
}
