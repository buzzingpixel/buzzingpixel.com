<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\Cart;

use App\Context\Cart\Entities\CurrentUserCart;
use App\Context\Stripe\LocalStripeApi;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

use function json_encode;

class CreateCheckoutSessionAction
{
    public function __construct(
        private LocalStripeApi $stripeApi,
        private CurrentUserCart $currentUserCart,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()
            ->withHeader(
                'Content-type',
                'application/json',
            );

        $response->getBody()->write(
            (string) json_encode(
                [
                    'sessionId' => $this->stripeApi->createCheckoutSession(
                        $this->currentUserCart->cart()
                    )->id,
                ],
            ),
        );

        return $response;
    }
}
