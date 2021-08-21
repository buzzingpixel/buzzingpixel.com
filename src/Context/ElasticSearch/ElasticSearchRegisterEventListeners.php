<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch;

use App\Context\ElasticSearch\EventListeners\SaveIssueAfterSaveIndexIssue;
use Crell\Tukio\OrderedListenerProvider;

class ElasticSearchRegisterEventListeners
{
    public static function register(OrderedListenerProvider $provider): void
    {
        $provider->addSubscriber(
            SaveIssueAfterSaveIndexIssue::class,
            SaveIssueAfterSaveIndexIssue::class,
        );
    }
}
