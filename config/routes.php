<?php

declare(strict_types=1);

use App\Http\Response\Account\AccountIndexAction;
use App\Http\Response\Account\BillingPortal\BillingPortalAction;
use App\Http\Response\Account\ChangePassword\ChangePasswordAction;
use App\Http\Response\Account\ChangePassword\PostChangePasswordAction;
use App\Http\Response\Account\Licenses\AccountLicenseAddAuthorizedDomainAction;
use App\Http\Response\Account\Licenses\AccountLicenseCancelSubscriptionAction;
use App\Http\Response\Account\Licenses\AccountLicenseDeleteAuthorizedDomainAction;
use App\Http\Response\Account\Licenses\AccountLicenseEditNotesAction;
use App\Http\Response\Account\Licenses\AccountLicenseResumeSubscriptionAction;
use App\Http\Response\Account\Licenses\AccountLicensesAction;
use App\Http\Response\Account\Licenses\AccountLicensesDetailAction;
use App\Http\Response\Account\Licenses\AccountLicenseStartNewSubscriptionAction;
use App\Http\Response\Account\Licenses\DownloadBundles\DownloadBundleAction;
use App\Http\Response\Account\Licenses\Downloads\DownloadAction;
use App\Http\Response\Account\Licenses\PostAccountLicenseCancelSubscriptionAction;
use App\Http\Response\Account\Licenses\PostAccountLicenseEditNotesAction;
use App\Http\Response\Account\Licenses\PostAccountLicensesAddAuthorizedDomainAction;
use App\Http\Response\Account\PostCheckoutAction;
use App\Http\Response\Account\Profile\AccountProfileAction;
use App\Http\Response\Account\Profile\PostAccountProfileAction;
use App\Http\Response\Account\Purchases\AccountPurchasesAction;
use App\Http\Response\Account\Purchases\AccountPurchasesDetailAction;
use App\Http\Response\Admin\AdminIndexAction;
use App\Http\Response\Admin\Analytics\AnalyticsViewAction;
use App\Http\Response\Admin\Licenses\LicenseListing\LicenseListingAction;
use App\Http\Response\Admin\NewLicense\NewLicenseIndex\NewLicenseAction;
use App\Http\Response\Admin\NewLicense\NewLicenseIndex\PostNewLicenseAction;
use App\Http\Response\Admin\Orders\PaginatedIndex\PaginatedOrdersAction;
use App\Http\Response\Admin\Queue\QueueIndex\QueueIndexAction;
use App\Http\Response\Admin\Software\AdminSoftwareAction;
use App\Http\Response\Admin\Software\Create\AdminSoftwareCreateAction;
use App\Http\Response\Admin\Software\Create\PostAdminSoftwareCreateAction;
use App\Http\Response\Admin\Software\CreateVersion\AdminCreateSoftwareVersionAction;
use App\Http\Response\Admin\Software\CreateVersion\PostAdminCreateSoftwareVersionAction;
use App\Http\Response\Admin\Software\Delete\DeleteSoftwareAction;
use App\Http\Response\Admin\Software\DeleteVersion\DeleteVersionAction;
use App\Http\Response\Admin\Software\DownloadVersion\DownloadVersionAction;
use App\Http\Response\Admin\Software\Edit\AdminSoftwareEditAction;
use App\Http\Response\Admin\Software\Edit\PostAdminSoftwareEditAction;
use App\Http\Response\Admin\Software\EditVersion\AdminEditVersionAction;
use App\Http\Response\Admin\Software\EditVersion\PostAdminEditVersionAction;
use App\Http\Response\Admin\Software\View\SoftwareViewAction;
use App\Http\Response\Admin\Software\ViewVersion\SoftwareViewVersionAction;
use App\Http\Response\Admin\Users\Create\CreateUserAction;
use App\Http\Response\Admin\Users\Create\PostCreateUserAction;
use App\Http\Response\Admin\Users\Delete\DeleteUserAction;
use App\Http\Response\Admin\Users\Edit\EditUserAction;
use App\Http\Response\Admin\Users\Edit\PostEditUserAction;
use App\Http\Response\Admin\Users\LogInAs\LogInAsAction;
use App\Http\Response\Admin\Users\PaginatedIndex\PaginatedUsersAction;
use App\Http\Response\Admin\Users\View\PostUserAddAuthorizedDomainAction;
use App\Http\Response\Admin\Users\View\PostUserLicenseEditAdminNotesAction;
use App\Http\Response\Admin\Users\View\UserCancelLicenseSubscriptionAction;
use App\Http\Response\Admin\Users\View\UserLicenseAddAuthorizedDomainAction;
use App\Http\Response\Admin\Users\View\UserLicenseDeleteAuthorizedDomainAction;
use App\Http\Response\Admin\Users\View\UserLicenseDisableAction;
use App\Http\Response\Admin\Users\View\UserLicenseEditAdminNotesAction;
use App\Http\Response\Admin\Users\View\UserLicenseEnableAction;
use App\Http\Response\Admin\Users\View\UserResumeLicenseSubscriptionAction;
use App\Http\Response\Admin\Users\View\ViewUserLicenseDetailAction;
use App\Http\Response\Admin\Users\View\ViewUserLicensesAction;
use App\Http\Response\Admin\Users\View\ViewUserProfileAction;
use App\Http\Response\Admin\Users\View\ViewUserPurchaseDetailAction;
use App\Http\Response\Admin\Users\View\ViewUserPurchasesAction;
use App\Http\Response\Ajax\FileUpload\FileUploadAction;
use App\Http\Response\Ajax\PostAnalyticPageView\PostAnalyticPageViewAction;
use App\Http\Response\Ajax\User\GetUserPayloadAction;
use App\Http\Response\Api\CheckLicense\CheckLicenseAction;
use App\Http\Response\Contact\ContactAction;
use App\Http\Response\Contact\PostContactAction;
use App\Http\Response\HealthCheck\HealthCheckAction;
use App\Http\Response\Home\HomeAction;
use App\Http\Response\IForgot\IForgotAction;
use App\Http\Response\IForgot\PostIForgotAction;
use App\Http\Response\LogIn\PostLogInAction;
use App\Http\Response\LogIn\PostLogOutAction;
use App\Http\Response\News\Detail\NewsDetailAction;
use App\Http\Response\News\PaginatedIndex\PaginatedIndexAction;
use App\Http\Response\Register\PostRegisterAction;
use App\Http\Response\Register\RegisterAction;
use App\Http\Response\ResetPwWithToken\PostResetPwWithTokenAction;
use App\Http\Response\ResetPwWithToken\ResetPwWithTokenAction;
use App\Http\Response\Software\AnselCraft\AnselCraftAction;
use App\Http\Response\Software\AnselCraft\AnselCraftChangelogAction;
use App\Http\Response\Software\AnselCraft\AnselCraftChangelogItemAction;
use App\Http\Response\Software\AnselCraft\Documentation\V1\AnselCraftV1DocFieldTypeSettingsAction;
use App\Http\Response\Software\AnselCraft\Documentation\V1\AnselCraftV1DocFieldTypeUseAction;
use App\Http\Response\Software\AnselCraft\Documentation\V1\AnselCraftV1DocIndexAction;
use App\Http\Response\Software\AnselCraft\Documentation\V1\AnselCraftV1DocTemplatingAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocFieldTypeSettingsAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocFieldTypeUseAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocIndexAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocTemplatingAction;
use App\Http\Response\Software\AnselEE\AnselEEAction;
use App\Http\Response\Software\AnselEE\AnselEEChangelogAction;
use App\Http\Response\Software\AnselEE\AnselEEChangelogItemAction;
use App\Http\Response\Software\AnselEE\AnselEEDownloadAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2DocIndexAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2DocTemplatingAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2FieldTypeSettingsAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2FieldTypeUseAction;
use App\Http\Response\Software\CategoryConstruct\CategoryConstructAction;
use App\Http\Response\Software\CategoryConstruct\CategoryConstructChangelogAction;
use App\Http\Response\Software\CategoryConstruct\CategoryConstructChangelogItemAction;
use App\Http\Response\Software\CategoryConstruct\Documentation\V2\CategoryConstructV2DocIndexAction;
use App\Http\Response\Software\CategoryConstruct\Documentation\V2\CategoryConstructV2DocTemplateTagsAction;
use App\Http\Response\Software\ChangelogFeed\ChangelogFeedAction;
use App\Http\Response\Software\Construct\ConstructAction;
use App\Http\Response\Software\Construct\ConstructChangelogAction;
use App\Http\Response\Software\Construct\ConstructChangelogItemAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2DocConfigRoutingAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2DocControlPanelAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2DocFieldTypesAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2DocIndexAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2DocRoutingAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2DocTemplateTagsAction;
use App\Http\Response\Software\Construct\Documentation\V2\ConstructV2ExtensionHookAction;
use App\Http\Response\Software\SoftwareAction;
use App\Http\Response\Software\SoftwarePurchaseAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocDevelopersAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocIndexAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocLocationAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocTemplateTagsAction;
use App\Http\Response\Software\Treasury\TreasuryAction;
use App\Http\Response\Software\Treasury\TreasuryChangelogAction;
use App\Http\Response\Software\Treasury\TreasuryChangelogItemAction;
use App\Http\Response\StandardPage\StandardPageAction;
use App\Http\Response\Stripe\Webhook\PostCheckoutSessionCompletedAction;
use App\Http\Response\Support\Dashboard\DashboardAction;
use App\Http\Response\Support\EditIssue\EditIssueAction;
use App\Http\Response\Support\EditIssue\PostEditIssueAction;
use App\Http\Response\Support\IssueDisplay\IssueDisplayAction;
use App\Http\Response\Support\IssueListing\AllIssuesPaginatedIndexAction;
use App\Http\Response\Support\IssueListing\MyIssuesPaginatedIndexAction;
use App\Http\Response\Support\NewIssue\NewIssueAction;
use App\Http\Response\Support\NewIssue\PostNewIssueAction;
use App\Http\Response\Support\Replies\EditReplyAction;
use App\Http\Response\Support\Replies\PostAddReplyAction;
use App\Http\Response\Support\Replies\PostEditReplyAction;
use App\Http\Response\Support\Search\SearchPublicPlusUsersIssuesAction;
use App\Http\Response\Support\Subscribe\SubscribeAction;
use App\Http\Response\Support\Subscribe\UnsubscribeAction;
use App\Http\RouteMiddleware\Admin\RequireAdminAction;
use App\Http\RouteMiddleware\LogIn\RequireLogInAction;
use App\Http\RouteMiddleware\Support\RequireDisplayName\RequireDisplayName;
use Config\NoOp;
use Config\Tinker;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', HomeAction::class);

    $app->get('/healthcheck/8HYhJLtmQA', HealthCheckAction::class);

    /**
     * Tinker
     */
    if ((bool) getenv('DEV_MODE')) {
        $app->get('/tinker', Tinker::class);
    }

    $app->get('/ajax/user/payload', GetUserPayloadAction::class);
    $app->post('/ajax/file-upload', FileUploadAction::class);
    $app->post('/ajax/analytics/page-view', PostAnalyticPageViewAction::class);

    $app->post('/account/log-in', PostLogInAction::class);
    $app->any('/account/log-out', PostLogOutAction::class);
    $app->get('/account/register', RegisterAction::class);
    $app->post('/account/register', PostRegisterAction::class);
    $app->get('/account/iforgot', IForgotAction::class);
    $app->post('/account/iforgot', PostIForgotAction::class);
    $app->get('/reset-pw-with-token/{token}', ResetPwWithTokenAction::class);
    $app->post('/reset-pw-with-token/{token}', PostResetPwWithTokenAction::class);

    /**
     * Admin
     */
    $app->group('/admin', function (RouteCollectorProxy $r): void {
        // $this so PHPCS will be happy and not convert to static function.
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress InvalidScope
         * @psalm-suppress MixedMethodCall
         */
        $this->get(NoOp::class);

        $r->get('', AdminIndexAction::class);

        /** Software */
        $r->get('/software', AdminSoftwareAction::class);
        $r->get('/software/create', AdminSoftwareCreateAction::class);
        $r->post('/software/create', PostAdminSoftwareCreateAction::class);
        $r->get('/software/{slug}', SoftwareViewAction::class);
        $r->get('/software/{slug}/edit', AdminSoftwareEditAction::class);
        $r->post('/software/{slug}/edit', PostAdminSoftwareEditAction::class);
        $r->any('/software/{slug}/delete', DeleteSoftwareAction::class);
        $r->get('/software/{softwareSlug}/add-version', AdminCreateSoftwareVersionAction::class);
        $r->post('/software/{softwareSlug}/add-version', PostAdminCreateSoftwareVersionAction::class);
        $r->get('/software/{softwareSlug}/version/{versionSlug}', SoftwareViewVersionAction::class);
        $r->get('/software/{softwareSlug}/version/{versionSlug}/edit', AdminEditVersionAction::class);
        $r->post('/software/{softwareSlug}/version/{versionSlug}/edit', PostAdminEditVersionAction::class);
        $r->any('/software/{softwareSlug}/version/{versionSlug}/delete', DeleteVersionAction::class);
        $r->get('/software/{softwareSlug}/version/{versionSlug}/download', DownloadVersionAction::class);

        /** Users */
        $r->get('/users', PaginatedUsersAction::class);
        $r->get('/users/create', CreateUserAction::class);
        $r->post('/users/create', PostCreateUserAction::class);
        $r->get('/users/{emailAddress}', ViewUserProfileAction::class);
        $r->get('/users/{emailAddress}/log-in-as', LogInAsAction::class);
        $r->get('/users/{emailAddress}/edit', EditUserAction::class);
        $r->post('/users/{emailAddress}/edit', PostEditUserAction::class);
        $r->any('/users/{emailAddress}/delete', DeleteUserAction::class);
        $r->get('/users/{emailAddress}/purchases', ViewUserPurchasesAction::class);
        $r->get('/users/{emailAddress}/purchases/{orderId}', ViewUserPurchaseDetailAction::class);
        $r->get('/users/{emailAddress}/licenses', ViewUserLicensesAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}', ViewUserLicenseDetailAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/disable-license', UserLicenseDisableAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/enable-license', UserLicenseEnableAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/cancel-subscription', UserCancelLicenseSubscriptionAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/resume-subscription', UserResumeLicenseSubscriptionAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/add-authorized-domain', UserLicenseAddAuthorizedDomainAction::class);
        $r->post('/users/{emailAddress}/licenses/{licenseKey}/add-authorized-domain', PostUserAddAuthorizedDomainAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/delete-authorized-domain/{domainName}', UserLicenseDeleteAuthorizedDomainAction::class);
        $r->get('/users/{emailAddress}/licenses/{licenseKey}/edit-admin-notes', UserLicenseEditAdminNotesAction::class);
        $r->post('/users/{emailAddress}/licenses/{licenseKey}/edit-admin-notes', PostUserLicenseEditAdminNotesAction::class);

        /** Orders */
        $r->get('/orders', PaginatedOrdersAction::class);

        /** Licenses */
        $r->get('/licenses', LicenseListingAction::class);

        /** New License */
        $r->get('/new-license', NewLicenseAction::class);
        $r->post('/new-license', PostNewLicenseAction::class);

        /** Queue */
        $r->get('/queue', QueueIndexAction::class);

        /** Analytics */
        $r->get('/analytics', AnalyticsViewAction::class);
    })->add(RequireAdminAction::class)
    ->add(RequireLogInAction::class);

    /**
     * Account
     */
    $app->group('/account', function (RouteCollectorProxy $r): void {
        // $this so PHPCS will be happy and not convert to static function.
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress InvalidScope
         * @psalm-suppress MixedMethodCall
         */
        $this->get(NoOp::class);

        $r->get('', AccountIndexAction::class);

        $r->get('/post-checkout', PostCheckoutAction::class);

        $r->get('/licenses', AccountLicensesAction::class);
        $r->get('/licenses/{licenseKey}', AccountLicensesDetailAction::class);
        $r->get('/licenses/{licenseKey}/add-authorized-domain', AccountLicenseAddAuthorizedDomainAction::class);
        $r->post('/licenses/{licenseKey}/add-authorized-domain', PostAccountLicensesAddAuthorizedDomainAction::class);
        $r->get('/licenses/{licenseKey}/delete-authorized-domain/{domainName}', AccountLicenseDeleteAuthorizedDomainAction::class);
        $r->get('/licenses/{licenseKey}/edit-notes', AccountLicenseEditNotesAction::class);
        $r->post('/licenses/{licenseKey}/edit-notes', PostAccountLicenseEditNotesAction::class);
        $r->get('/licenses/{licenseKey}/cancel-subscription', AccountLicenseCancelSubscriptionAction::class);
        $r->post('/licenses/{licenseKey}/cancel-subscription', PostAccountLicenseCancelSubscriptionAction::class);
        $r->get('/licenses/{licenseKey}/resume-subscription', AccountLicenseResumeSubscriptionAction::class);
        $r->get('/licenses/{licenseKey}/start-new-subscription', AccountLicenseStartNewSubscriptionAction::class);
        $r->get('/licenses/{licenseKey}/download', DownloadAction::class);
        $r->get('/licenses/{licenseKey}/download/{softwareSlug}', DownloadBundleAction::class);

        $r->get('/purchases', AccountPurchasesAction::class);
        $r->get('/purchases/{orderId}', AccountPurchasesDetailAction::class);

        $r->get('/profile', AccountProfileAction::class);
        $r->post('/profile', PostAccountProfileAction::class);

        $r->get('/change-password', ChangePasswordAction::class);
        $r->post('/change-password', PostChangePasswordAction::class);

        $r->get('/billing-portal', BillingPortalAction::class);
    })->add(RequireLogInAction::class);

    /**
     * Software
     */
    $app->get('/software', SoftwareAction::class);
    $app->get('/software/{softwareSlug}/purchase', SoftwarePurchaseAction::class)
        ->setArguments(['heading' => 'Log in or create an account to purchase'])
        ->add(RequireLogInAction::class);

    /**
     * Ansel for Craft
     */
    $app->get('/software/ansel-craft', AnselCraftAction::class);
    $app->get('/software/ansel-craft/changelog', AnselCraftChangelogAction::class);
    $app->get('/software/ansel-craft/changelog/feed', ChangelogFeedAction::class)
        ->setArgument('for', 'ansel-craft');
    $app->get('/software/ansel-craft/changelog/{slug:[^\/]+}', AnselCraftChangelogItemAction::class);

    // Ansel for Craft Current Docs
    $app->get('/software/ansel-craft/documentation', AnselCraftDocIndexAction::class);
    $app->get('/software/ansel-craft/documentation/field-type-settings', AnselCraftDocFieldTypeSettingsAction::class);
    $app->get('/software/ansel-craft/documentation/field-type-use', AnselCraftDocFieldTypeUseAction::class);
    $app->get('/software/ansel-craft/documentation/templating', AnselCraftDocTemplatingAction::class);

    // Ansel for Craft Legacy V1 Docs
    $app->get('/software/ansel-craft/documentation/v1', AnselCraftV1DocIndexAction::class);
    $app->get('/software/ansel-craft/documentation/v1/field-type-settings', AnselCraftV1DocFieldTypeSettingsAction::class);
    $app->get('/software/ansel-craft/documentation/v1/field-type-use', AnselCraftV1DocFieldTypeUseAction::class);
    $app->get('/software/ansel-craft/documentation/v1/templating', AnselCraftV1DocTemplatingAction::class);

    /**
     * Ansel for ExpressionEngine
     */
    $app->get('/software/ansel-ee', AnselEEAction::class);
    $app->get('/software/ansel-ee/changelog', AnselEEChangelogAction::class);
    $app->get('/software/ansel-ee/changelog/feed', ChangelogFeedAction::class)
        ->setArgument('for', 'ansel-ee');
    $app->get('/software/ansel-ee/changelog/{slug:[^\/]+}', AnselEEChangelogItemAction::class);
    $app->get('/software/ansel-ee/download', AnselEEDownloadAction::class);

    // Ansel for EE Current Docs
    $app->get('/software/ansel-ee/documentation', AnselEEV2DocIndexAction::class);
    $app->get('/software/ansel-ee/documentation/field-type-settings', AnselEEV2FieldTypeSettingsAction::class);
    $app->get('/software/ansel-ee/documentation/field-type-use', AnselEEV2FieldTypeUseAction::class);
    $app->get('/software/ansel-ee/documentation/templating', AnselEEV2DocTemplatingAction::class);

    /**
     * Treasury
     */
    $app->get('/software/treasury', TreasuryAction::class);
    $app->get('/software/treasury/changelog', TreasuryChangelogAction::class);
    $app->get('/software/treasury/changelog/feed', ChangelogFeedAction::class)
        ->setArgument('for', 'treasury');
    $app->get('/software/treasury/changelog/{slug:[^\/]+}', TreasuryChangelogItemAction::class);

    // Treasury docs
    $app->get('/software/treasury/documentation', TreasuryV1DocIndexAction::class);
    $app->get('/software/treasury/documentation/locations', TreasuryV1DocLocationAction::class);
    $app->get('/software/treasury/documentation/template-tags', TreasuryV1DocTemplateTagsAction::class);
    $app->get('/software/treasury/documentation/developers', TreasuryV1DocDevelopersAction::class);

    /**
     * Construct
     */
    $app->get('/software/construct', ConstructAction::class);
    $app->get('/software/construct/changelog', ConstructChangelogAction::class);
    $app->get('/software/construct/changelog/feed', ChangelogFeedAction::class)
        ->setArgument('for', 'construct');
    $app->get('/software/construct/changelog/{slug:[^\/]+}', ConstructChangelogItemAction::class);

    // Construct Docs
    $app->get('/software/construct/documentation', ConstructV2DocIndexAction::class);
    $app->get('/software/construct/documentation/control-panel', ConstructV2DocControlPanelAction::class);
    $app->get('/software/construct/documentation/field-types', ConstructV2DocFieldTypesAction::class);
    $app->get('/software/construct/documentation/routing', ConstructV2DocRoutingAction::class);
    $app->get('/software/construct/documentation/config-routing', ConstructV2DocConfigRoutingAction::class);
    $app->get('/software/construct/documentation/template-tags', ConstructV2DocTemplateTagsAction::class);
    $app->redirect('/software/construct/documentation/extension-hook', '/software/construct/documentation/extension-hooks');
    $app->get('/software/construct/documentation/extension-hooks', ConstructV2ExtensionHookAction::class);

    /**
     * Category Construct
     */
    $app->get('/software/category-construct', CategoryConstructAction::class);
    $app->get('/software/category-construct/changelog', CategoryConstructChangelogAction::class);
    $app->get('/software/category-construct/changelog/feed', ChangelogFeedAction::class)
        ->setArgument('for', 'category-construct');
    $app->get('/software/category-construct/changelog/{slug:[^\/]+}', CategoryConstructChangelogItemAction::class);

    // Category Construct Docs
    $app->get('/software/category-construct/documentation', CategoryConstructV2DocIndexAction::class);
    $app->get('/software/category-construct/documentation/template-tags', CategoryConstructV2DocTemplateTagsAction::class);

    // Stripe
    $app->post(
        '/stripe/webhook/checkout-session-completed',
        PostCheckoutSessionCompletedAction::class,
    );

    // News
    $app->get('/news[/page/{pageNum:\d+}]', PaginatedIndexAction::class);
    $app->get('/news/{slug}', NewsDetailAction::class);

    // Contact
    $app->get('/contact', ContactAction::class);
    $app->post('/contact', PostContactAction::class);

    // Support
    $app->get('/support', DashboardAction::class);
    $app->get('/support/all-issues[/page/{pageNum:\d+}]', AllIssuesPaginatedIndexAction::class);
    $app->get('/support/my-issues[/page/{pageNum:\d+}]', MyIssuesPaginatedIndexAction::class)
        ->setArguments(['heading' => 'Log in to view your issues'])
        ->add(RequireLogInAction::class);
    $app->get('/support/search[/page/{pageNum:\d+}]', SearchPublicPlusUsersIssuesAction::class);
    $app->redirect('/support/log-in', '/support')
        ->setArguments(['heading' => 'Log in to view your issues or open a new one'])
        ->add(RequireLogInAction::class);
    $app->get('/support/new-issue', NewIssueAction::class)
        ->setName('CreateNewIssue')
        ->setArguments(['heading' => 'Log in to create a new issue'])
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class);
    $app->post('/support/new-issue', PostNewIssueAction::class)
        ->setArguments(['heading' => 'Log in to create a new issue'])
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class);
    $app->get('/support/issue/{issueNumber}', IssueDisplayAction::class)
        ->setName('IssueDisplay');
    $app->get('/support/issue/{issueNumber}/edit', EditIssueAction::class)
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class)
        ->setName('IssueEdit');
    $app->post('/support/issue/{issueNumber}/edit', PostEditIssueAction::class)
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class)
        ->setName('PostIssueEdit');
    $app->post('/support/issue/{issueNumber}/add-reply', PostAddReplyAction::class)
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class)
        ->setName('PostAddIssueReply');
    $app->get('/support/issue/{issueNumber}/edit-reply/{replyId}', EditReplyAction::class)
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class)
        ->setName('EditIssueReply');
    $app->post('/support/issue/{issueNumber}/edit-reply/{replyId}', PostEditReplyAction::class)
        ->add(RequireDisplayName::class)
        ->add(RequireLogInAction::class)
        ->setName('PostEditIssueReply');
    $app->get('/support/issue/{issueNumber}/unsubscribe', UnsubscribeAction::class)
        ->setArguments(['heading' => 'Log in to unsubscribe'])
        ->add(RequireLogInAction::class)
        ->setName('IssueUnsubscribe');
    $app->get('/support/issue/{issueNumber}/subscribe', SubscribeAction::class)
        ->setArguments(['heading' => 'Log in to subscribe'])
        ->add(RequireLogInAction::class)
        ->setName('IssueSubscribe');

    /**
     * API
     */
    $app->post('/api/v1/check-license', CheckLicenseAction::class)
        ->setName('ApiCheckLicense');

    /**
     * Pages
     */
    $app->get('/cookies', StandardPageAction::class)
        ->setArgument(
            'contentPath',
            'content/pages/cookie-policy.md'
        );
    $app->get('/privacy', StandardPageAction::class)
        ->setArgument(
            'contentPath',
            'content/pages/privacy-policy.md'
        );
    $app->get('/terms', StandardPageAction::class)
        ->setArgument(
            'contentPath',
            'content/pages/terms-of-service.md'
        );
};
