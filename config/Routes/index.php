<?php

declare(strict_types=1);

use App\Http\Response\Ajax\User\GetUserPayloadAction;
use App\Http\Response\Home\HomeAction;
use App\Http\Response\IForgot\IForgotAction;
use App\Http\Response\IForgot\PostIForgotAction;
use App\Http\Response\LogIn\PostLogInAction;
use App\Http\Response\LogIn\PostLogOutAction;
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
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2DocIndexAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2DocTemplatingAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2FieldTypeSettingsAction;
use App\Http\Response\Software\AnselEE\Documentation\V2\AnselEEV2FieldTypeUseAction;
use App\Http\Response\Software\CategoryConstruct\CategoryConstructAction;
use App\Http\Response\Software\CategoryConstruct\CategoryConstructChangelogAction;
use App\Http\Response\Software\CategoryConstruct\CategoryConstructChangelogItemAction;
use App\Http\Response\Software\CategoryConstruct\Documentation\V2\CategoryConstructV2DocIndexAction;
use App\Http\Response\Software\CategoryConstruct\Documentation\V2\CategoryConstructV2DocTemplateTagsAction;
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
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocDevelopersAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocIndexAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocLocationAction;
use App\Http\Response\Software\Treasury\Documentation\V1\TreasuryV1DocTemplateTagsAction;
use App\Http\Response\Software\Treasury\TreasuryAction;
use App\Http\Response\Software\Treasury\TreasuryChangelogAction;
use App\Http\Response\Software\Treasury\TreasuryChangelogItemAction;
use App\Http\RouteMiddleware\LogIn\RequireLogInAction;
use Config\NoOp;
use Config\Tinker;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get(pattern: '/', callable: HomeAction::class);

    /**
     * Tinker
     */
    if ((bool) getenv('DEV_MODE')) {
        $app->get(pattern: '/tinker', callable: Tinker::class);
    }

    $app->get(pattern: '/ajax/user/payload', callable: GetUserPayloadAction::class);

    $app->post(pattern: '/account/log-in', callable: PostLogInAction::class);
    $app->any(pattern: '/account/log-out', callable: PostLogOutAction::class);
    $app->get(pattern: '/account/register', callable: RegisterAction::class);
    $app->post(pattern: '/account/register', callable: PostRegisterAction::class);
    $app->get(pattern: '/account/iforgot', callable: IForgotAction::class);
    $app->post(pattern: '/account/iforgot', callable: PostIForgotAction::class);
    $app->get(pattern: '/reset-pw-with-token/{token}', callable: ResetPwWithTokenAction::class);
    $app->post(pattern: '/reset-pw-with-token/{token}', callable: PostResetPwWithTokenAction::class);

    /**
     * Account
     */
    $app->group(pattern: '/account', callable: function (RouteCollectorProxy $r): void {
        // $this so PHPCS will be happy and not convert to static function.
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress InvalidScope
         * @psalm-suppress MixedMethodCall
         */
        $this->get(NoOp::class);

        $r->get(pattern: '', callable: function (): void {
            // $this so PHPCS will be happy and not convert to static function.
            /**
             * @phpstan-ignore-next-line
             * @psalm-suppress InvalidScope
             * @psalm-suppress MixedMethodCall
             */
            $this->get(NoOp::class);

            // TODO: Account Page
            dd('TODO: account page');
        });
    })->add(RequireLogInAction::class);

    /**
     * Software
     */
    $app->get(pattern: '/software', callable: SoftwareAction::class);

    /**
     * Ansel for Craft
     */
    $app->get(pattern: '/software/ansel-craft', callable: AnselCraftAction::class);
    $app->get(pattern: '/software/ansel-craft/changelog', callable: AnselCraftChangelogAction::class);
    $app->get(pattern: '/software/ansel-craft/changelog/{slug:[^\/]+}', callable: AnselCraftChangelogItemAction::class);

    // Ansel for Craft Current Docs
    $app->get(pattern: '/software/ansel-craft/documentation', callable: AnselCraftDocIndexAction::class);
    $app->get(pattern: '/software/ansel-craft/documentation/field-type-settings', callable: AnselCraftDocFieldTypeSettingsAction::class);
    $app->get(pattern: '/software/ansel-craft/documentation/field-type-use', callable: AnselCraftDocFieldTypeUseAction::class);
    $app->get(pattern: '/software/ansel-craft/documentation/templating', callable: AnselCraftDocTemplatingAction::class);

    // Ansel for Craft Legacy V1 Docs
    $app->get(pattern: '/software/ansel-craft/documentation/v1', callable: AnselCraftV1DocIndexAction::class);
    $app->get(pattern: '/software/ansel-craft/documentation/v1/field-type-settings', callable: AnselCraftV1DocFieldTypeSettingsAction::class);
    $app->get(pattern: '/software/ansel-craft/documentation/v1/field-type-use', callable: AnselCraftV1DocFieldTypeUseAction::class);
    $app->get(pattern: '/software/ansel-craft/documentation/v1/templating', callable: AnselCraftV1DocTemplatingAction::class);

    /**
     * Ansel for ExpressionEngine
     */
    $app->get(pattern: '/software/ansel-ee', callable: AnselEEAction::class);
    $app->get(pattern: '/software/ansel-ee/changelog', callable: AnselEEChangelogAction::class);
    $app->get(pattern: '/software/ansel-ee/changelog/{slug:[^\/]+}', callable: AnselEEChangelogItemAction::class);

    // Ansel for EE Current Docs
    $app->get(pattern: '/software/ansel-ee/documentation', callable: AnselEEV2DocIndexAction::class);
    $app->get(pattern: '/software/ansel-ee/documentation/field-type-settings', callable: AnselEEV2FieldTypeSettingsAction::class);
    $app->get(pattern: '/software/ansel-ee/documentation/field-type-use', callable: AnselEEV2FieldTypeUseAction::class);
    $app->get(pattern: '/software/ansel-ee/documentation/templating', callable: AnselEEV2DocTemplatingAction::class);

    /**
     * Treasury
     */
    $app->get(pattern: '/software/treasury', callable: TreasuryAction::class);
    $app->get(pattern: '/software/treasury/changelog', callable: TreasuryChangelogAction::class);
    $app->get(pattern: '/software/treasury/changelog/{slug:[^\/]+}', callable: TreasuryChangelogItemAction::class);

    // Treasury docs
    $app->get(pattern: '/software/treasury/documentation', callable: TreasuryV1DocIndexAction::class);
    $app->get(pattern: '/software/treasury/documentation/locations', callable: TreasuryV1DocLocationAction::class);
    $app->get(pattern: '/software/treasury/documentation/template-tags', callable: TreasuryV1DocTemplateTagsAction::class);
    $app->get(pattern: '/software/treasury/documentation/developers', callable: TreasuryV1DocDevelopersAction::class);

    /**
     * Construct
     */
    $app->get(pattern: '/software/construct', callable: ConstructAction::class);
    $app->get(pattern: '/software/construct/changelog', callable: ConstructChangelogAction::class);
    $app->get(pattern: '/software/construct/changelog/{slug:[^\/]+}', callable: ConstructChangelogItemAction::class);

    // Construct Docs
    $app->get(pattern: '/software/construct/documentation', callable: ConstructV2DocIndexAction::class);
    $app->get(pattern: '/software/construct/documentation/control-panel', callable: ConstructV2DocControlPanelAction::class);
    $app->get(pattern: '/software/construct/documentation/field-types', callable: ConstructV2DocFieldTypesAction::class);
    $app->get(pattern: '/software/construct/documentation/routing', callable: ConstructV2DocRoutingAction::class);
    $app->get(pattern: '/software/construct/documentation/config-routing', callable: ConstructV2DocConfigRoutingAction::class);
    $app->get(pattern: '/software/construct/documentation/template-tags', callable: ConstructV2DocTemplateTagsAction::class);
    $app->redirect('/software/construct/documentation/extension-hook', '/software/construct/documentation/extension-hooks');
    $app->get(pattern: '/software/construct/documentation/extension-hooks', callable: ConstructV2ExtensionHookAction::class);

    /**
     * Category Construct
     */
    $app->get(pattern: '/software/category-construct', callable: CategoryConstructAction::class);
    $app->get(pattern: '/software/category-construct/changelog', callable: CategoryConstructChangelogAction::class);
    $app->get(pattern: '/software/category-construct/changelog/{slug:[^\/]+}', callable: CategoryConstructChangelogItemAction::class);

    // Category Construct Docs
    $app->get(pattern: '/software/category-construct/documentation', callable: CategoryConstructV2DocIndexAction::class);
    $app->get(pattern: '/software/category-construct/documentation/template-tags', callable: CategoryConstructV2DocTemplateTagsAction::class);
};
