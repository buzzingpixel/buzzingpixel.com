<?php

declare(strict_types=1);

namespace App\Context\Issues\EventListeners;

use App\Context\Issues\Events\SaveIssueFailed;
use App\Context\RedisCache\CacheItemPool;
use Psr\Cache\InvalidArgumentException;

class SaveIssueFailedRemoveCachedReplyAmount
{
    public function __construct(private CacheItemPool $cacheItemPool)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onSaveFailed(SaveIssueFailed $saveIssueFailed): void
    {
        $issueId = $saveIssueFailed->issue->id();

        $this->cacheItemPool->deleteItem(
            key: 'save_issue_prev_reply_count_' . $issueId,
        );
    }
}
