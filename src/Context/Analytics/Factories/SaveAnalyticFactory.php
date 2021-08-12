<?php

declare(strict_types=1);

namespace App\Context\Analytics\Factories;

use App\Context\Analytics\Contracts\SaveAnalyticContract;
use App\Context\Analytics\Services\SaveAnalyticExisting;
use App\Context\Analytics\Services\SaveAnalyticNew;
use App\Persistence\Entities\Analytics\AnalyticsRecord;

class SaveAnalyticFactory
{
    public function __construct(
        private SaveAnalyticNew $saveAnalyticNew,
        private SaveAnalyticExisting $saveAnalyticExisting,
    ) {
    }

    public function createSaveAnalytic(
        ?AnalyticsRecord $record = null
    ): SaveAnalyticContract {
        if ($record === null) {
            return $this->saveAnalyticNew;
        }

        return $this->saveAnalyticExisting;
    }
}
