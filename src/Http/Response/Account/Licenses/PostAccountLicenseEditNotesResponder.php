<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostAccountLicenseEditNotesResponder
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(
        Payload $payload,
        string $redirectTo,
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
                    'result' => ['message' => 'Notes edited'],
                ]
            );
        }

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $redirectTo);
    }
}
