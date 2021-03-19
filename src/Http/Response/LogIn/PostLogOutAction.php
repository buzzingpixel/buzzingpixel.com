<?php

declare(strict_types=1);

namespace App\Http\Response\LogIn;

use App\Context\Users\UserApi;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

class PostLogOutAction
{
    public function __construct(
        private UserApi $userApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->userApi->logCurrentUserOut();

        $postData = $request->getParsedBody();

        $redirectTo = '/';

        if (is_array($postData)) {
            $redirectTo = (string) ($postData['redirect_to'] ?? '/');
        }

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $redirectTo);
    }
}
