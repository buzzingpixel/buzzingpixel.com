<?php

declare(strict_types=1);

namespace App\Http\RouteMiddleware\Support\RequireDisplayName\Factories;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\RouteMiddleware\Support\RequireDisplayName\Contracts\RequireDisplayNameResponderContract;
use App\Http\RouteMiddleware\Support\RequireDisplayName\Responder\RequireDisplayNameResponderInvalid;
use App\Http\RouteMiddleware\Support\RequireDisplayName\Responder\RequireDisplayNameResponderPassThrough;
use App\Http\RouteMiddleware\Support\RequireDisplayName\Responder\RequireDisplayNameResponderRequire;

class RequireDisplayNameResponderFactory
{
    public function __construct(
        private RequireDisplayNameResponderInvalid $invalid,
        private RequireDisplayNameResponderRequire $require,
        private RequireDisplayNameResponderPassThrough $passThrough,
    ) {
    }

    public function getResponder(
        LoggedInUser $loggedInUser,
    ): RequireDisplayNameResponderContract {
        if ($loggedInUser->hasNoUser()) {
            return $this->invalid;
        }

        if ($loggedInUser->user()->supportProfile()->displayName() === '') {
            return $this->require;
        }

        return $this->passThrough;
    }
}
