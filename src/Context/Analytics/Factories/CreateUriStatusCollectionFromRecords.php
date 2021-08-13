<?php

declare(strict_types=1);

namespace App\Context\Analytics\Factories;

use App\Context\Analytics\Entities\UriStats;
use App\Context\Analytics\Entities\UriStatsCollection;
use App\Persistence\Entities\Analytics\AnalyticsRecord;

use function array_values;
use function count;
use function ksort;

class CreateUriStatusCollectionFromRecords
{
    /**
     * @param AnalyticsRecord[] $records
     *
     * @phpstan-ignore-next-line
     */
    public function create(array $records): UriStatsCollection
    {
        if (count($records) < 1) {
            return new UriStatsCollection();
        }

        $entities = [];

        $visitors = [];

        foreach ($records as $record) {
            $uri      = $record->getUri();
            $cookieId = $record->getCookieId()->toString();

            if (! isset($entities[$uri])) {
                $uriStats = new UriStats(
                    uri: $uri,
                );

                $visitors[$uri] = [];

                $entities[$uri] = $uriStats;
            }

            if (! isset($visitors[$uri][$cookieId])) {
                $visitors[$uri][$cookieId] = $cookieId;

                $e           = $entities[$uri];
                $total       = $e->totalVisitorsInTimeRange();
                $totalUnique = $e->totalUniqueVisitorsInTimeRange();

                $entities[$uri] = new UriStats(
                    uri: $uri,
                    totalVisitorsInTimeRange: $total,
                    totalUniqueVisitorsInTimeRange: $totalUnique + 1,
                );
            }

            $e           = $entities[$uri];
            $total       = $e->totalVisitorsInTimeRange();
            $totalUnique = $e->totalUniqueVisitorsInTimeRange();

            $entities[$uri] = new UriStats(
                uri: $uri,
                totalVisitorsInTimeRange: $total + 1,
                totalUniqueVisitorsInTimeRange: $totalUnique,
            );
        }

        ksort($entities);

        return new UriStatsCollection(array_values($entities));
    }
}
