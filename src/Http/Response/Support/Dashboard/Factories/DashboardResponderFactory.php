<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Dashboard\Contracts\DashboardResponderContract;
use App\Http\Response\Support\Dashboard\Responders\DashboardResponderLoggedIn;
use App\Http\Response\Support\Dashboard\Responders\DashboardResponderLoggedOut;

class DashboardResponderFactory
{
    public function __construct(
        private DashboardResponderLoggedIn $dashboardResponderLoggedIn,
        private DashboardResponderLoggedOut $dashboardResponderLoggedOut,
    ) {
    }

    public function getResponder(
        LoggedInUser $loggedInUser
    ): DashboardResponderContract {
        if ($loggedInUser->hasUser()) {
            return $this->dashboardResponderLoggedIn;
        }

        return $this->dashboardResponderLoggedOut;
    }
}
