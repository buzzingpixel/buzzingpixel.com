<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\PostAnalyticPageView;

use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Ajax\PostAnalyticPageView\Cookies\CookieFactory;
use App\Http\Response\Ajax\PostAnalyticPageView\PageView\CreatePageViewFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostAnalyticPageViewAction
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private CookieFactory $cookieFactory,
        private CreatePageViewFactory $createPageViewFactory,
        private PostAnalyticsPageViewResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $requestData = (array) $request->getParsedBody();

        $this->createPageViewFactory->create(
            user: $this->loggedInUser->userOrNull(),
        )->create(
            cookie: $this->cookieFactory->create(),
            user: $this->loggedInUser->userOrNull(),
            uri: (string) ($requestData['uri'] ?? '/'),
        );

        return $this->responder->respond();
    }
}
