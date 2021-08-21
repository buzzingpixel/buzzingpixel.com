<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\EventListeners;

use App\Context\ElasticSearch\Services\IndexIssue\IndexIssue;
use App\Context\Issues\Events\SaveIssueAfterSave;

class SaveIssueAfterSaveIndexIssue
{
    public function __construct(private IndexIssue $indexIssue)
    {
    }

    public function onAfterSave(SaveIssueAfterSave $afterSave): void
    {
        $this->indexIssue->indexIssue($afterSave->issue);
    }
}
