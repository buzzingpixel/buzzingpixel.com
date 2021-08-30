<?php

declare(strict_types=1);

namespace App\Context\Issues\EventListeners;

use App\Context\Issues\Events\SaveIssueBeforeSave;
use App\Context\Issues\Services\SetIssueSubscribers\SetSubscribers;

class SaveIssueBeforeSaveSetSubscribers
{
    public function __construct(private SetSubscribers $setSubscribers)
    {
    }

    public function onBeforeSave(SaveIssueBeforeSave $beforeSave): void
    {
        $beforeSave->issue = $this->setSubscribers->set(
            issue: $beforeSave->issue,
        );
    }
}
