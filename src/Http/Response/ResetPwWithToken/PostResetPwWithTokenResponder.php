<?php

declare(strict_types=1);

namespace App\Http\Response\ResetPwWithToken;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostResetPwWithTokenResponder
{
    private FlashMessages $flashMessages;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        FlashMessages $flashMessages,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->flashMessages   = $flashMessages;
        $this->responseFactory = $responseFactory;
    }

    public function respond(
        Payload $payload,
        string $redirectTo
    ): ResponseInterface {
        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
            $this->flashMessages->addMessage(
                'FormMessage',
                [
                    'status' => $payload->getStatus(),
                    'result' => $payload->getResult(),
                ]
            );
        } else {
            $this->flashMessages->addMessage(
                'FormMessage',
                [
                    'status' => Payload::STATUS_SUCCESSFUL,
                    'result' => [
                        'message' => 'Your password was updated successfully.' .
                            ' You can now log in with your new password.',
                    ],
                ]
            );
        }

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $redirectTo);
    }
}
