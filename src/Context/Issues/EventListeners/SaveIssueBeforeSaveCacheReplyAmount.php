<?php

declare(strict_types=1);

namespace App\Context\Issues\EventListeners;

use App\Context\Cache\Entities\CacheItem;
use App\Context\Issues\Events\SaveIssueBeforeSave;
use App\Context\Issues\Services\FetchTotalReplies\FetchTotalReplies;
use App\Context\RedisCache\CacheItemPool;
use App\Persistence\QueryBuilders\Issues\IssueMessageQueryBuilder;
use DateInterval;
use DateTimeImmutable;

class SaveIssueBeforeSaveCacheReplyAmount
{
    public function __construct(
        private CacheItemPool $cacheItemPool,
        private FetchTotalReplies $fetchTotalReplies,
    ) {
    }

    public function onBeforeSave(SaveIssueBeforeSave $beforeSave): void
    {
        if ($beforeSave->isNew) {
            return;
        }

        $issueId = $beforeSave->issue->id();

        $totalReplies = $this->fetchTotalReplies->fetch(
            queryBuilder: (new IssueMessageQueryBuilder())
                ->withIssueId(value: $issueId),
        );

        $this->cacheItemPool->save(new CacheItem(
            key: 'save_issue_prev_reply_count_' . $issueId,
            value: $totalReplies,
            expiresAt: (new DateTimeImmutable())->add(
                new DateInterval('PT1M'),
            ),
        ));
    }
}
