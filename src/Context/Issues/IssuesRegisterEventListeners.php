<?php

declare(strict_types=1);

namespace App\Context\Issues;

use App\Context\Issues\EventListeners\SaveIssueAfterSaveSendNotifications;
use App\Context\Issues\EventListeners\SaveIssueBeforeSaveCacheReplyAmount;
use App\Context\Issues\EventListeners\SaveIssueBeforeSaveSetInitialSubscribers;
use App\Context\Issues\EventListeners\SaveIssueBeforeSaveSetNewIssueNumber;
use App\Context\Issues\EventListeners\SaveIssueFailedRemoveCachedReplyAmount;
use Crell\Tukio\OrderedListenerProvider;

class IssuesRegisterEventListeners
{
    public static function register(OrderedListenerProvider $provider): void
    {
        $provider->addSubscriber(
            SaveIssueBeforeSaveSetNewIssueNumber::class,
            SaveIssueBeforeSaveSetNewIssueNumber::class,
        );

        $provider->addSubscriber(
            SaveIssueBeforeSaveSetInitialSubscribers::class,
            SaveIssueBeforeSaveSetInitialSubscribers::class,
        );

        $provider->addSubscriber(
            SaveIssueBeforeSaveCacheReplyAmount::class,
            SaveIssueBeforeSaveCacheReplyAmount::class,
        );

        $provider->addSubscriber(
            SaveIssueFailedRemoveCachedReplyAmount::class,
            SaveIssueFailedRemoveCachedReplyAmount::class,
        );

        $provider->addSubscriber(
            SaveIssueAfterSaveSendNotifications::class,
            SaveIssueAfterSaveSendNotifications::class,
        );
    }
}
