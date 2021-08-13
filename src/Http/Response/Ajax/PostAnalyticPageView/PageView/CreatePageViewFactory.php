<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\PostAnalyticPageView\PageView;

use App\Context\Users\Entities\User;

class CreatePageViewFactory
{
    public function __construct(
        private CreatePageView $createPageView,
        private CreatePageViewNoOp $createPageViewNoOp,
    ) {
    }

    public function create(?User $user = null): CreatePageViewContract
    {
        if ($user !== null && $user->isAdmin()) {
            return $this->createPageViewNoOp;
        }

        return $this->createPageView;
    }
}
