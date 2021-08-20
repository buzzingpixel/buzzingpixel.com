<?php

declare(strict_types=1);

namespace App\Context\Issues;

use App\Context\Issues\EventListeners\SaveIssueBeforeSaveSetNewIssueNumber;
use Crell\Tukio\OrderedListenerProvider;

class IssuesRegisterEventListeners
{
    public static function register(OrderedListenerProvider $provider): void
    {
        $provider->addSubscriber(
            SaveIssueBeforeSaveSetNewIssueNumber::class,
            SaveIssueBeforeSaveSetNewIssueNumber::class,
        );
    }
}
