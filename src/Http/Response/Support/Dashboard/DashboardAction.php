<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Dashboard;

use App\Context\Content\ContentApi;
use App\Context\Path\PathApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\Dashboard\Factories\DashboardResponderFactory;
use Config\General;
use Psr\Http\Message\ResponseInterface;

class DashboardAction
{
    public function __construct(
        private General $config,
        private PathApi $pathApi,
        private ContentApi $contentApi,
        private LoggedInUser $loggedInUser,
        private DashboardResponderFactory $dashboardResponderFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->dashboardResponderFactory
            ->getResponder(loggedInUser: $this->loggedInUser)
            ->respond(
                supportMenu: $this->config->supportMenu(active: 'dashboard'),
                supportArticles: $this->contentApi->contentItemsFromDirectory(
                    path: $this->pathApi->pathFromProjectRoot(
                        path: 'content/support-articles'
                    ),
                ),
            );
    }
}
