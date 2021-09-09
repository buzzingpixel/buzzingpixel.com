<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\LogInAs\Services;

use App\Http\Response\Admin\Users\LogInAs\Contracts\ResponderContract;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class ResponderError implements ResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(Payload $payload): ResponseInterface
    {
        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => $payload->getStatus(),
                'result' => $payload->getResult(),
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/admin/users');
    }
}
