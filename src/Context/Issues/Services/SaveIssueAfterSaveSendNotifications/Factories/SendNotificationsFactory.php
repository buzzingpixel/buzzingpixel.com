<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Factories;

use App\Context\Issues\Events\SaveIssueAfterSave;
use App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Contracts\SendNotificationsContract;
use App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Services\SendNotifications;
use App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Services\SendNotificationsNoOp;

class SendNotificationsFactory
{
    public function __construct(
        private SendNotifications $send,
        private SendNotificationsNoOp $noOp,
    ) {
    }

    public function get(
        int $previousReplyAmount,
        SaveIssueAfterSave $afterSave,
    ): SendNotificationsContract {
        if ($afterSave->wasNew) {
            return $this->send;
        }

        $messageAmount = $afterSave->issue->issueMessages()->count();

        if ($previousReplyAmount < $messageAmount) {
            return $this->send;
        }

        return $this->noOp;
    }
}
