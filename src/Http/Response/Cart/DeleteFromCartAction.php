<?php

declare(strict_types=1);

namespace App\Http\Response\Cart;

use App\Context\Cart\CartApi;
use App\Context\Cart\Entities\CurrentUserCart;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteFromCartAction
{
    public function __construct(
        private CurrentUserCart $currentUserCart,
        private CartApi $cartApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $softwareSlug = (string) $request->getAttribute('softwareSlug');

        $cart = $this->currentUserCart->cart()->withRemovedCartItemBySlug(
            $softwareSlug,
        );

        $this->cartApi->saveCart($cart);

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/cart');
    }
}
