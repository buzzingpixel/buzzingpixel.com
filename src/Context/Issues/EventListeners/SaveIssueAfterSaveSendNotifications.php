<?php

declare(strict_types=1);

namespace App\Context\Issues\EventListeners;

use App\Context\Issues\Events\SaveIssueAfterSave;
use App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Factories\SendNotificationsFactory;
use App\Context\RedisCache\CacheItemPool;
use Psr\Cache\InvalidArgumentException;

class SaveIssueAfterSaveSendNotifications
{
    public function __construct(
        private CacheItemPool $cacheItemPool,
        private SendNotificationsFactory $sendNotificationsFactory,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onAfterSave(SaveIssueAfterSave $afterSave): void
    {
        $issueId = $afterSave->issue->id();

        $cacheKey = 'save_issue_prev_reply_count_' . $issueId;

        $cachedPreviousReplyAmount = $this->cacheItemPool->getItem(
            key: $cacheKey,
        );

        $this->sendNotificationsFactory->get(
            previousReplyAmount: (int) $cachedPreviousReplyAmount->get(),
            afterSave: $afterSave,
        )->send(issue: $afterSave->issue, wasNew: $afterSave->wasNew);

        $this->cacheItemPool->deleteItem(key: $cacheKey);
    }
}
