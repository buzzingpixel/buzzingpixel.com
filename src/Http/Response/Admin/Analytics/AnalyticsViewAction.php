<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Analytics;

use App\Context\Analytics\AnalyticsApi;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Safe\DateTimeImmutable;
use Safe\Exceptions\DatetimeException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnalyticsViewAction
{
    public function __construct(
        private AnalyticsApi $analyticsApi,
        private AnalyticsViewResponder $responder,
    ) {
    }

    /**
     * @throws DatetimeException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        $twentyFourHoursAgo = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'))
            ->modify('-24 hours');

        $thirtyDaysAgo = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'))
            ->modify('-30 days');

        return $this->responder->respond(
            pageViews24Hours: $this->analyticsApi->getTotalPageViewsSince(
                date: $twentyFourHoursAgo,
            ),
            uniqueVisitors24Hours: $this->analyticsApi
                ->getUniqueTotalVisitorsSince(date: $twentyFourHoursAgo),
            uriStats24Hours: $this->analyticsApi->getUriStatsSince(
                date: $twentyFourHoursAgo,
            ),
            totalPageViews30Days: $this->analyticsApi->getTotalPageViewsSince(
                date: $thirtyDaysAgo,
            ),
            totalVisitors30Days: $this->analyticsApi->getUniqueTotalVisitorsSince(
                date: $thirtyDaysAgo,
            ),
        );
    }
}
