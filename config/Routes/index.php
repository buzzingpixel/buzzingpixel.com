<?php

declare(strict_types=1);

use App\Http\Response\Home\HomeAction;
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
use App\Http\Response\Software\SoftwareAction;
use Slim\App;

return static function (App $app): void {
    $app->get(pattern: '/', callable: HomeAction::class);

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
};
