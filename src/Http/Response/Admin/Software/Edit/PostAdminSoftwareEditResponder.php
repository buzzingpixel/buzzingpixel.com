<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Edit;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostAdminSoftwareEditResponder
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    /**
     * @param mixed[] $postData
     */
    public function respond(
        Payload $payload,
        string $redirectTo,
        array $postData,
    ): ResponseInterface {
        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
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
                    'result' => ['message' => 'Software saved!'],
                ]
            );
        }

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $redirectTo);
    }
}
