<?php

declare(strict_types=1);

namespace App\Http\Response\Cart;

use App\Context\Cart\CartApi;
use App\Payload\Payload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

class CheckoutSuccessAction
{
    public function __construct(
        private CartApi $cartApi,
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $this->cartApi->clearCurrentUserCart();

        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'result' => [
                    'message' => 'You checked out successfully. ' .
                        'If your order does not appear yet, please allow a ' .
                        'minute or two as the system syncs with Stripe,' .
                        ' then refresh the page.',
                ],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/account/purchases');
    }
}
