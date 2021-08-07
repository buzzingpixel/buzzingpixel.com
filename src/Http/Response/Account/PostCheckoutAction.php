<?php

declare(strict_types=1);

namespace App\Http\Response\Account;

use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class PostCheckoutAction
{
    public function __construct(
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'result' => [
                    'message' => 'You checked out successfully. ' .
                        'If your order does not appear yet, please allow a ' .
                        'minute or two as your order syncs, then refresh ' .
                        'the page.',
                ],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/account/licenses');
    }
}
