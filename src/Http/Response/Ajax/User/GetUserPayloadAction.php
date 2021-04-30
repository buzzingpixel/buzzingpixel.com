<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\User;

use App\Context\Users\Entities\LoggedInUser;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

class GetUserPayloadAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private LoggedInUser $loggedInUser,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader(
                'Content-type',
                'application/json'
            );

        $userIsLoggedIn = $this->loggedInUser->hasUser();

        $response->getBody()->write((string) json_encode(
            [
                'userIsLoggedIn' => $userIsLoggedIn,
                'userEmailAddress' => $userIsLoggedIn ?
                    $this->loggedInUser->user()->emailAddress() :
                    '',
                'userIsAdmin' => $userIsLoggedIn &&
                    $this->loggedInUser->user()->isAdmin(),
            ],
        ));

        return $response;
    }
}
