<?php

declare(strict_types=1);

namespace App\Http\Response\IForgot;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostIForgotResponder
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

    public function respond(): ResponseInterface
    {
        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'result' => [
                    'message' => 'If that email address is associated with an' .
                        " account and you haven't requested a reset more than 5" .
                        " times in the last 2 hours, we'll send password reset" .
                        ' instructions to that email address.',
                ],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader(
                'Location',
                '/account'
            );
    }
}
