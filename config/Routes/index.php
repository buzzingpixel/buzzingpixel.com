<?php

declare(strict_types=1);

use App\Http\Response\Home\HomeAction;
use App\Http\Response\Software\AnselCraft\AnselCraftAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocFieldTypeSettingsAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocFieldTypeUseAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocIndexAction;
use App\Http\Response\Software\AnselCraft\Documentation\V2\AnselCraftDocTemplatingAction;
use App\Http\Response\Software\SoftwareAction;
use Slim\App;

return static function (App $app): void {
    $app->get('/', HomeAction::class);

    /**
     * Software
     */
    $app->get('/software', SoftwareAction::class);

    /**
     * Ansel for Craft
     */
    $app->get('/software/ansel-craft', AnselCraftAction::class);

    // Ansel for Craft Current Docs
    $app->get('/software/ansel-craft/documentation', AnselCraftDocIndexAction::class);
    $app->get('/software/ansel-craft/documentation/field-type-settings', AnselCraftDocFieldTypeSettingsAction::class);
    $app->get('/software/ansel-craft/documentation/field-type-use', AnselCraftDocFieldTypeUseAction::class);
    $app->get('/software/ansel-craft/documentation/templating', AnselCraftDocTemplatingAction::class);
};
