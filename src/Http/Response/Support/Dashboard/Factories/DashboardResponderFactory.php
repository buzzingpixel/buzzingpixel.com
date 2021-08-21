<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Dashboard\Contracts\DashboardResponderContract;
use App\Http\Response\Support\Dashboard\Responders\DashboardResponderAdmin;
use App\Http\Response\Support\Dashboard\Responders\DashboardResponderLoggedIn;
use App\Http\Response\Support\Dashboard\Responders\DashboardResponderLoggedOut;

class DashboardResponderFactory
{
    public function __construct(
        private DashboardResponderAdmin $dashboardResponderAdmin,
        private DashboardResponderLoggedIn $dashboardResponderLoggedIn,
        private DashboardResponderLoggedOut $dashboardResponderLoggedOut,
    ) {
    }

    public function getResponder(
        LoggedInUser $loggedInUser
    ): DashboardResponderContract {
        return $this->dashboardResponderLoggedIn;

        if ($loggedInUser->hasNoUser()) {
            return $this->dashboardResponderLoggedOut;
        }

        if ($loggedInUser->user()->isNotAdmin()) {
            return $this->dashboardResponderLoggedIn;
        }

        return $this->dashboardResponderAdmin;
    }
}
