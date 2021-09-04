<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Analytics;

use App\Context\Analytics\Entities\UriStatsCollection;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnalyticsViewResponder
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @phpstan-ignore-next-line
     */
    public function respond(
        int $pageViews24Hours,
        int $uniqueVisitors24Hours,
        UriStatsCollection $uriStats24Hours,
        int $totalPageViews30Days,
        int $totalVisitors30Days,
    ): ResponseInterface {
        $adminMenu = $this->config->adminMenu();

        $adminMenu['analytics']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/Analytics/AnalyticsView.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Analytics | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stats' => [
                    'last24Hours' => [
                        'totalPageViews' => $pageViews24Hours,
                        'uniqueTotalVisitors' => $uniqueVisitors24Hours,
                        'uriStatsModels' => $uriStats24Hours,
                    ],
                    'last30Days' => [
                        'totalPageViews' => $totalPageViews30Days,
                        'uniqueTotalVisitors' => $totalVisitors30Days,
                    ],
                ],
            ],
        ));

        return $response;
    }
}
