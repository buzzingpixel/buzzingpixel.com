<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Contracts;

use App\Context\Issues\Entities\Issue;

interface SendNotificationsContract
{
    public function send(Issue $issue, bool $wasNew = false): void;
}
