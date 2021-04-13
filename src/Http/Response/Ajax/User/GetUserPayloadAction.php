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

        $response->getBody()->write((string) json_encode(
            [
                'userIsLoggedIn' => $this->loggedInUser->hasUser(),
                'userEmailAddress' => $this->loggedInUser->hasUser() ?
                    $this->loggedInUser->user()->emailAddress() :
                    '',
                'userIsAdmin' => $this->loggedInUser->user()->isAdmin(),
            ],
        ));

        return $response;
    }
}
