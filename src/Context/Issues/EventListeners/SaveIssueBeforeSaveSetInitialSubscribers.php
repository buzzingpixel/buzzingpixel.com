<?php

declare(strict_types=1);

namespace App\Context\Issues\EventListeners;

use App\Context\Issues\Events\SaveIssueBeforeSave;
use App\Context\Issues\Services\SaveIssueBeforeSaveSetInitialSubscribers\Factories\SetInitialSubscribersFactory;

class SaveIssueBeforeSaveSetInitialSubscribers
{
    public function __construct(
        private SetInitialSubscribersFactory $setInitialSubscribersFactory
    ) {
    }

    public function onBeforeSave(SaveIssueBeforeSave $beforeSave): void
    {
        $beforeSave->issue = $this->setInitialSubscribersFactory
            ->get(beforeSave: $beforeSave)
            ->set(issue: $beforeSave->issue);
    }
}
