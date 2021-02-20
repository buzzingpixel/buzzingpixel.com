<?php

declare(strict_types=1);

use App\Http\Response\Home\HomeAction;
use App\Http\Response\Software\AnselCraft\AnselCraftAction;
use App\Http\Response\Software\SoftwareAction;
use Slim\App;

return static function (App $app): void {
    $app->get('/', HomeAction::class);
    $app->get('/software', SoftwareAction::class);
    $app->get('/software/ansel-craft', AnselCraftAction::class);
};
