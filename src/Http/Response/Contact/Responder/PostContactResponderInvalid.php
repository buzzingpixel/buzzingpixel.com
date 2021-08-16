<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\Responder;

use App\Http\Response\Contact\Entities\FormValues;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostContactResponderInvalid implements PostContactResponderContract
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(FormValues $formValues): ResponseInterface
    {
        $nameMap = [
            'your_name' => 'Your Name: ',
            'your_email' => 'Your Email Address: ',
            'message' => 'Message: ',
        ];

        $messageList = [];

        foreach ($formValues->errorMessages() as $key => $val) {
            $messageList[] = $nameMap[$key] . $val;
        }

        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_ERROR,
                'result' => [
                    'message' => 'Unable process your submission',
                    'messageList' => $messageList,
                ],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/contact');
    }
}
