<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\Responder;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostNewLicenseResponderInvalid implements PostNewLicenseResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function respond(Payload $payload): ResponseInterface
    {
        $msg = 'Valid Email address and software is required';

        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_NOT_VALID,
                'result' => ['message' => $msg],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/admin/new-license');
    }
}
