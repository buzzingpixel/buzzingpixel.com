<?php

declare(strict_types=1);

namespace App\Context\Issues\Events;

use App\Context\Issues\Entities\Issue;
use App\Events\StoppableEvent;

class SaveIssueBeforeValidate extends StoppableEvent
{
    public function __construct(
        public Issue $issue,
        public bool $isNew,
        public bool $setNewIssueNumber = true,
    ) {
    }
}
