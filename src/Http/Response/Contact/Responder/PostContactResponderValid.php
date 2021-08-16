<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\Responder;

use App\Http\Response\Contact\Entities\FormValues;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostContactResponderValid implements PostContactResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(FormValues $formValues): ResponseInterface
    {
        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'result' => [
                    'message' => "Your email was sent and I'll respond to it " .
                        'as soon as I can. Have a great day!',
                ],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/contact');
    }
}
