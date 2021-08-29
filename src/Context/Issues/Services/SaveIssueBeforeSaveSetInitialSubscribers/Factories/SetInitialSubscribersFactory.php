<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Factories;

use App\Context\Issues\Events\SaveIssueBeforeSave;
use App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Contracts\SetInitialSubscribersContract;
use App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Services\SetInitialSubscribers;
use App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Services\SetInitialSubscribersNoOp;

class SetInitialSubscribersFactory
{
    public function __construct(
        private SetInitialSubscribers $setInitialSubscribers,
        private SetInitialSubscribersNoOp $setInitialSubscribersNoOp,
    ) {
    }

    public function get(
        SaveIssueBeforeSave $beforeSave
    ): SetInitialSubscribersContract {
        if ($beforeSave->isNew) {
            return $this->setInitialSubscribers;
        }

        return $this->setInitialSubscribersNoOp;
    }
}
