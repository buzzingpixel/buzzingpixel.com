<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Services;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Contracts\SendNotificationsContract;

class SendNotificationsNoOp implements SendNotificationsContract
{
    public function send(Issue $issue, bool $wasNew = false): void
    {
    }
}
