<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\Create;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostCreateUserResponder
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

    /**
     * @param mixed[] $postData
     */
    public function respond(
        Payload $payload,
        string $redirectTo,
        array $postData,
    ): ResponseInterface {
        if ($payload->getStatus() !== Payload::STATUS_CREATED) {
            $this->flashMessages->addMessage(
                'FormMessage',
                [
                    'status' => $payload->getStatus(),
                    'result' => $payload->getResult(),
                    'post_data' => $postData,
                ]
            );
        } else {
            $this->flashMessages->addMessage(
                'FormMessage',
                [
                    'status' => Payload::STATUS_SUCCESSFUL,
                    'result' => ['message' => 'Account created successfully!'],
                ]
            );
        }

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $redirectTo);
    }
}
